<?php namespace Lovata\Toolbox\Classes\Api\Collection;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Arr;
use Lovata\Toolbox\Classes\Api\Error\MethodNotFoundException;
use Lovata\Toolbox\Classes\Api\Type\AbstractObjectType;
use Lovata\Toolbox\Classes\Api\Type\Custom\PaginationInfoType;
use Lovata\Toolbox\Classes\Api\Type\Input\FilterCollectionInputType;
use Lovata\Toolbox\Classes\Api\Type\Input\PaginateInputType;
use Lovata\Toolbox\Classes\Collection\ElementCollection;

/**
 * Class AbstractCollectionType
 * @package Lovata\Toolbox\Classes\Api\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractCollectionType extends AbstractObjectType
{
    const COLLECTION_CLASS = '';
    const RELATED_ITEM_TYPE_CLASS     = '';

    /** @var ElementCollection|null */
    protected $obList = null;

    /** @var array */
    protected $arPageInfo = [];

    /** @var array */
    protected $arCustomArgumentList = [];

    /** @var string */
    protected $sFilterInputTypeClass = FilterCollectionInputType::class;

    /** @var string */
    protected $sSortEnumInputTypeClass = '';

    /** @var string */
    protected $sSortMethodName = 'sort';

    /**
     * getList
     * @return ElementCollection|null
     */
    public function getList(): ?ElementCollection
    {
        return $this->obList;
    }

    /**
     * setList
     * @param $obList
     * @return void
     */
    public function setList($obList)
    {
        $this->obList = $obList;
    }

    /**
     * Get filter input type class
     * @return string
     */
    public function getFilterInputTypeClass(): string
    {
        return $this->sFilterInputTypeClass;
    }

    /**
     * Set filter input type class
     * @param string $sClassName
     * @return string
     */
    public function setFilterInputTypeClass(string $sClassName): string
    {
        return $this->sFilterInputTypeClass = $sClassName;
    }

    /**
     * Get sort enum type class TYPE_ALIAS
     * @return string
     */
    public function getSortEnumInputTypeClass(): string
    {
        return $this->sSortEnumInputTypeClass;
    }

    /**
     * Set sort enum input type class
     * @param string $sClassName
     * @return string
     */
    public function setSortEnumInputTypeClass(string $sClassName): string
    {
        return $this->sSortEnumInputTypeClass = $sClassName;
    }

    /**
     * Set sort method name
     * @param string $sMethodName
     * @return void
     */
    public function setSortMethodName(string $sMethodName)
    {
        $this->sSortMethodName = $sMethodName;
    }

    /**
     * Add arguments
     * @param array $arArgumentList
     * @return void
     */
    public function addArguments(array $arArgumentList)
    {
        $this->arCustomArgumentList = array_merge($this->arCustomArgumentList, $arArgumentList);
    }

    /**
     * Get resolve method for type
     * @return callable|null
     */
    protected function getResolveMethod(): ?callable
    {
        return function ($obValue, $arArgumentList, $sContext, ResolveInfo $obResolveInfo) {
            //Check client authorization
            if (!$this->checkAuth()) {
                return null;
            }

            $this->arArgumentValueList = $arArgumentList;

            //Init collection class
            $sClassName = static::COLLECTION_CLASS;
            $this->obList = $sClassName::make();

            //Extend resolve method before filtering
            $this->extendResolveMethod($arArgumentList);

            //Check client access
            if (!$this->checkAccess($obResolveInfo->path, $this->obList, $this->arMethodList)) {
                return null;
            }

            //Apply filters
            $this->applyFilters($arArgumentList);

            //Apply sorting
            $this->applySorting($arArgumentList);

            //Get pagination info
            $this->getPaginationInfo($arArgumentList);

            $arResult = [
                'list'     => $this->obList,
                'pageInfo' => $this->arPageInfo,
            ];

            return $arResult;
        };
    }

    /**
     * Extend logic in resolve method
     * @param array $arArgumentList
     */
    protected function extendResolveMethod($arArgumentList)
    {
    }

    /**
     * Apply filters
     * @param $arArgumentList
     * @return void
     * @throws MethodNotFoundException
     */
    protected function applyFilters($arArgumentList)
    {
        $arFilterInput = Arr::get($arArgumentList, 'filter', []);
        foreach ($arFilterInput as $sMethodName => $mArguments) {
            if (!$this->methodExists($sMethodName)) {
                $sMessage = 'GraphQL: Method ' . $sMethodName . '() not implemented in ' . static::class . ' class';
                throw new MethodNotFoundException($sMessage);
            }

            call_user_func_array([$this, $sMethodName], [$mArguments]);
        }
    }

    /**
     * Apply sorting
     * @param $arArgumentList
     * @return void
     */
    protected function applySorting($arArgumentList)
    {
        $sSortInput = Arr::get($arArgumentList, 'sort');
        if (!isset($sSortInput)) {
            return;
        }

        $this->obList = call_user_func([$this->obList, $this->sSortMethodName], $sSortInput);
    }

    /**
     * Get pagination info
     * @param $arArgumentList
     */
    protected function getPaginationInfo($arArgumentList)
    {
        $arPaginationInput = Arr::get($arArgumentList, 'paginate');
        $iTotalItems = $this->obList->count();
        $iPage = Arr::get($arPaginationInput, 'page', PaginateInputType::PAGE_DEFAULT_VALUE);
        $iPerPage = Arr::get($arPaginationInput, 'perPage', PaginateInputType::PER_PAGE_DEFAULT_VALUE);
        $iTotalPages = ceil($iTotalItems / $iPerPage);
        $this->obList = $this->obList->page($iPage, $iPerPage);

        $this->arPageInfo = [
            'page' => $iPage,
            'perPage' => $iPerPage,
            'totalPages' => $iTotalPages,
            'totalItems' => $iTotalItems,
            'hasNextPage' => $iPage < $iTotalPages,
            'hasPreviousPage' => ($iTotalPages > 1 && $iPage > 1),
        ];

        $this->extendPageInfo($arPaginationInput);
    }

    /**
     * Extend pagination info data
     * @param $arPaginationInput
     * @return void
     */
    protected function extendPageInfo($arPaginationInput)
    {
    }

    /**
     * Get config for "args" attribute
     * @return array|null
     * @throws \GraphQL\Error\Error
     */
    protected function getArguments(): ?array
    {
        $arArgumentList = [
            'filter' => [
                'type' => $this->getRelationType($this->sFilterInputTypeClass::TYPE_ALIAS),
                'description' => 'Apply list filtration',
            ],
            'paginate' => [
                'type' => $this->getRelationType(PaginateInputType::TYPE_ALIAS),
                'description' => 'Setting pagination data',
            ],
        ];

        if (!empty($this->sSortEnumInputTypeClass)) {
            $arArgumentList['sort'] = [
                'type' => $this->getRelationType($this->sSortEnumInputTypeClass::TYPE_ALIAS),
                'description' => 'Sorting list',
            ];
        }

        $arArgumentList = array_merge($arArgumentList, $this->arCustomArgumentList);

        return $arArgumentList;
    }

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'list'     => [
                'type'        => Type::listOf($this->getRelationType(static::RELATED_ITEM_TYPE_CLASS::TYPE_ALIAS)),
                'description' => static::getDescription(),
            ],
            'pageInfo' => [
                'type'        => $this->getRelationType(PaginationInfoType::TYPE_ALIAS),
                'description' => 'Pagination info',
            ],
        ];

        return $arFieldList;
    }

    //
    // Filter methods
    //

    /**
     * Filter by sequence (applySorting method in ElementCollection).
     * Method applies array_intersect() function to array of element IDs $arElementIDList and collection.
     *
     * This method is related to filter, not sorting, because it affects the number of elements as a result
     * of its operation.
     *
     * @param $arResultIDList
     * @return void
     */
    protected function filterBySequence($arResultIDList)
    {
        $this->obList->applySorting($arResultIDList);
    }

    /**
     * Method returns new collection with next nearest elements.
     * @param $arInput
     * @return void
     */
    protected function getNearestNext($arInput)
    {
        $iElementID = (int) Arr::get($arInput, 'elementId');
        $iCount = Arr::get($arInput, 'count');
        $bCyclic = Arr::get($arInput, 'cyclic');
        $arResultIDList = $this->obList->getNearestNext($iElementID, $iCount, $bCyclic)->getIDList();
        $this->obList->set($arResultIDList);
    }

    /**
     * Method returns new collection with previous nearest elements.
     * @param $arInput
     * @return void
     */
    protected function getNearestPrev($arInput)
    {
        $iElementID = (int) Arr::get($arInput, 'elementId');
        $iCount = Arr::get($arInput, 'count');
        $bCyclic = Arr::get($arInput, 'cyclic');
        $arResultIDList = $this->obList->getNearestPrev($iElementID, $iCount, $bCyclic)->getIDList();
        $this->obList->set($arResultIDList);
    }

    /**
     * Method applies array_intersect() function to collection and array of element IDs $arElementIDList.
     * @param $arElementIDList
     * @return void
     */
    protected function intersect($arElementIDList)
    {
        $this->obList->intersect($arElementIDList);
    }

    /**
     * Method applies array_diff() function to collection and array of element IDs $arElementIDList
     * @param $arElementIDList
     * @return void
     */
    protected function diff($arElementIDList)
    {
        $this->obList->diff($arElementIDList);
    }

    /**
     * Method excludes element with ID = $iElementID from collection.
     * @param $iElementID
     * @return void
     */
    protected function exclude($iElementID)
    {
        $this->obList->exclude($iElementID);
    }

    /**
     * Method returns array of random ElementItem objects.
     * @param $iCount
     * @return void
     */
    protected function random($iCount)
    {
        if ($this->obList->isEmpty()) {
            $this->obList->all();
        }

        $arElementIdList = array_keys($this->obList->random($iCount));
        $this->obList->intersect($arElementIdList);
    }
}
