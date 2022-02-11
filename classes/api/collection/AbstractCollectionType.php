<?php namespace Lovata\Toolbox\Classes\Api\Collection;

use Lovata\Toolbox\Classes\Api\Type\AbstractApiType;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

use Lovata\Toolbox\Classes\Collection\ElementCollection;
use Lovata\Toolbox\Classes\Item\ElementItem;
use Illuminate\Support\Arr;
use Str;

/**
 * Class AbstractCollectionType
 * @package Lovata\Toolbox\Classes\Api\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractCollectionType extends AbstractApiType
{
    const COLLECTION_CLASS = '';
    const METHOD_LIST_BEFORE_COUNT = ['getIDList', 'find', 'all', 'take', 'page', 'first', 'last'];

    /** @var array $arArgumentList */
    protected $arCustomArgumentList = [];

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
            $this->extendResolveMethod($arArgumentList);

            //Init collection class
            $sClassName = static::COLLECTION_CLASS;
            $obList = $sClassName::make();
            $iCount = 0;

            //Get method list from arguments
            $arMethodList = Arr::get($arArgumentList, 'method');
            if (!empty($arMethodList) && is_array($arMethodList)) {
                foreach ($arMethodList as $sMethodName) {
                    // Save counter value before applying collection method
                    if (in_array($sMethodName, static::METHOD_LIST_BEFORE_COUNT)
                        && $obList instanceof ElementCollection
                    ) {
                        $iCount = $obList->count();
                    }

                    $arParamList = [];
                    $sParamMethodName = 'get'.Str::studly($sMethodName).'Param';
                    if ($this->methodExists($sParamMethodName)) {
                        $arParamList = $this->$sParamMethodName($arArgumentList);
                    } else {
                        $sValue = Arr::get($arArgumentList, $sMethodName);
                        if (!empty($sValue)) {
                            $arParamList[] = $sValue;
                        }
                    }

                    $obList = call_user_func_array([$obList, $sMethodName], $arParamList);
                }
            }

            $arResult = [
                'list'  => null,
                'item'  => null,
                'count' => $iCount,
            ];

            if ($obList instanceof ElementItem) {
                $arResult['item'] = $obList;
            } elseif (is_string($obList)) {
                $arResult['implode_string'] = $obList;
            } elseif ($obList instanceof ElementCollection) {
                $arResult['list'] = $obList->all();
                if ($arResult['count'] == 0) {
                    $arResult['count'] = $obList->count();
                }
            } else {
                $arResult['list'] = $obList;
            }

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
     * Get config for "args" attribute
     * @return array|null
     */
    protected function getArguments(): ?array
    {
        $arArgumentList = [
            'method'            => Type::listOf(Type::string()),
            'set'               => Type::listOf(Type::id()),
            'intersect'         => Type::listOf(Type::id()),
            'applySorting'      => Type::listOf(Type::id()),
            'merge'             => Type::listOf(Type::id()),
            'diff'              => Type::listOf(Type::id()),
            'find'              => Type::id(),
            'exclude'           => Type::id(),
            'skip'              => Type::int(),
            'take'              => Type::int(),
            'random'            => Type::int(),
            'currentPage'       => Type::int(),
            'countPerPage'      => Type::int(),
            'shiftCountPage'    => Type::int(),
            'unshift'           => Type::int(),
            'push'              => Type::int(),
            'implodeField'      => Type::string(),
            'implodeDelimiter'  => Type::string(),
            'nearestNextID'     => Type::int(),
            'nearestNextCount'  => Type::int(),
            'nearestNextCyclic' => Type::boolean(),
            'nearestPrevID'     => Type::int(),
            'nearestPrevCount'  => Type::int(),
            'nearestPrevCyclic' => Type::boolean(),
        ];

        $arArgumentList = array_merge($arArgumentList, $this->arCustomArgumentList);

        return $arArgumentList;
    }

    /**
     * Get type fields
     * @return array
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'count'          => Type::int(),
            'implode_string' => Type::string(),
        ];

        return $arFieldList;
    }

    /**
     * Get params for page() collection method
     * @param array $arArgumentList
     * @return array
     */
    protected function getPageParam($arArgumentList): array
    {
        $arResult = [
            Arr::get($arArgumentList, 'currentPage'),
        ];

        $iCountPerPage = Arr::get($arArgumentList, 'countPerPage');
        if ($iCountPerPage > 0) {
            $arResult[] = $iCountPerPage;
        }

        $iShiftCountPage = Arr::get($arArgumentList, 'shiftCountPage');
        if ($iShiftCountPage > 0) {
            $arResult[] = $iShiftCountPage;
        }

        return $arResult;
    }

    /**
     * Get params for implode() collection method
     * @param array $arArgumentList
     * @return array
     */
    protected function getImplodeParam($arArgumentList): array
    {
        $arResult = [
            Arr::get($arArgumentList, 'implodeField'),
        ];

        $sImplodeDelimiter = Arr::get($arArgumentList, 'implodeDelimiter');
        if (!empty($sImplodeDelimiter)) {
            $arResult[] = $sImplodeDelimiter;
        }

        return $arResult;
    }

    /**
     * Get params for getNearestNext() collection method
     * @param array $arArgumentList
     * @return array
     */
    protected function getGetNearestNextParam($arArgumentList): array
    {
        $arResult = [
            Arr::get($arArgumentList, 'nearestNextID'),
        ];

        $iCount = Arr::get($arArgumentList, 'nearestNextCount');
        if ($iCount > 0) {
            $arResult[] = $iCount;
            $arResult[] = Arr::get($arArgumentList, 'nearestNextCyclic');
        }

        return $arResult;
    }

    /**
     * Get params for getNearestPrev() collection method
     * @param array $arArgumentList
     * @return array
     */
    protected function getGetNearestPrevParam($arArgumentList): array
    {
        $arResult = [
            Arr::get($arArgumentList, 'nearestPrevID'),
        ];

        $iCount = Arr::get($arArgumentList, 'nearestPrevCount');
        if ($iCount > 0) {
            $arResult[] = $iCount;
            $arResult[] = Arr::get($arArgumentList, 'nearestPrevCyclic');
        }

        return $arResult;
    }
}
