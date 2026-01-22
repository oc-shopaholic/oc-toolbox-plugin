<?php namespace Lovata\Toolbox\Classes\Api\Item;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

use Lovata\Toolbox\Classes\Api\Response\ApiDataResponse;
use Lovata\Toolbox\Classes\Api\Type\AbstractApiType;
use Lovata\Toolbox\Classes\Api\Type\Custom\Type as CustomType;

use Illuminate\Support\Arr;
use Lang;
use Str;

/**
 * Class AbstractItemType
 * @package Lovata\Toolbox\Classes\Api\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractItemType extends AbstractApiType
{
    const ITEM_CLASS = '';

    protected $obItem = null;

    /** @var array */
    protected $arMethodList = [];

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

            $iElementID = Arr::get($arArgumentList, 'id');
            $this->obItem = $this->findElement($iElementID);

            //Get method list from arguments
            $this->arMethodList = Arr::get($arArgumentList, 'method');
            $this->extendResolveMethod($arArgumentList);

            //Check client access
            if (!$this->checkAccess($obResolveInfo->path, $this->obItem, $this->arMethodList)) {
                return null;
            }

            if (empty($this->obItem) || $this->obItem->isEmpty()) {
                ApiDataResponse::instance()->setErrorMessage(
                    ApiDataResponse::CODE_NOT_FOUND,
                    Lang::get('lovata.toolbox::lang.message.'.ApiDataResponse::CODE_NOT_FOUND),
                );

                return null;
            }

            //Apply methods to ElementItem
            if (!empty($this->arMethodList) && is_array($this->arMethodList)) {
                foreach ($this->arMethodList as $sMethodName) {
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

                    $this->obItem = call_user_func_array([$this->obItem, $sMethodName], $arParamList);
                }
            }

            return $this->obItem;
        };
    }

    /**
     * Returns element item by ID
     * @param int|string $iElementID
     * @return \Lovata\Toolbox\Classes\Item\ElementItem
     */
    protected function findElement($iElementID)
    {
        if (empty($iElementID)) {
            return null;
        }

        $sItemClass = static::ITEM_CLASS;
        /** @var \Lovata\Toolbox\Classes\Item\ElementItem $obItem */
        $obItem = $sItemClass::make($iElementID);

        return $obItem;
    }

    /**
     * Get config for "args" attribute
     * @return array|null
     */
    protected function getArguments(): ?array
    {
        $arArgumentList = [
            'id'     => Type::id(),
            'method' => Type::listOf(Type::string()),
        ];

        return $arArgumentList;
    }

    /**
     * Get attachOne file fields
     * @param $sFieldName
     * @return array[]
     */
    protected function getAttachOneFileFields($sFieldName): array
    {
        return [
            $sFieldName => [
                'type'    => CustomType::array(),
                'resolve' => function ($obItem) use ($sFieldName) {
                    return [
                        'url'         => ($obItem->$sFieldName) ? $obItem->$sFieldName->getPath() : null,
                        'title'       => Arr::get($obItem->$sFieldName, 'title'),
                        'description' => Arr::get($obItem->$sFieldName, 'description'),
                        'file_name'   => Arr::get($obItem->$sFieldName, 'file_name'),
                    ];
                }
            ]
        ];
    }

    /**
     * Get attachMany file fields
     * @return array[]
     */
    protected function getAttachManyFileFields($sFieldName): array
    {
        return [
            $sFieldName => [
                'type'    => CustomType::array(),
                'resolve' => function ($obItem) use ($sFieldName) {
                    return $this->getFileListData($obItem, $sFieldName);
                },
            ],
        ];
    }

    /**
     * Get file list data
     * @param $obItem
     * @param $sFieldName
     * @return array
     */
    protected function getFileListData($obItem, $sFieldName): array
    {
        $obFileList = $obItem->{$sFieldName};
        $arFileList = [];

        if (empty($obFileList)) {
            return $arFileList;
        }

        foreach ($obFileList as $obFile) {
            $arFileData = [
                'url'         => $obFile->getPath(),
                'title'       => Arr::get($obFile->attributes, 'title'),
                'description' => Arr::get($obFile->attributes, 'description'),
                'file_name'   => Arr::get($obFile->attributes, 'file_name'),
            ];

            $arFileList[] = $arFileData;
        }

        return $arFileList;
    }

    /**
     * Extend logic in resolve method
     * @param array $arArgumentList
     */
    protected function extendResolveMethod($arArgumentList)
    {
    }
}
