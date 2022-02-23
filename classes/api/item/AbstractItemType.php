<?php namespace Lovata\Toolbox\Classes\Api\Item;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

use Lovata\Toolbox\Classes\Api\Type\AbstractApiType;
use Lovata\Toolbox\Classes\Api\Type\Custom\Type as CustomType;

use Illuminate\Support\Arr;
use Str;

/**
 * Class AbstractItemType
 * @package Lovata\Toolbox\Classes\Api\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractItemType extends AbstractApiType
{
    const ITEM_CLASS = '';

    /**
     * Get resolve method for type
     * @return callable|null
     */
    protected function getResolveMethod(): ?callable
    {
        return function ($obValue, $arArgumentList, $sContext, ResolveInfo $obResolveInfo) {
            $iElementID = Arr::get($arArgumentList, 'id');

            $obItem = $this->findElement($iElementID);
            if (empty($obItem) || $obItem->isEmpty()) {
                return null;
            }

            //Get method list from arguments
            $arMethodList = Arr::get($arArgumentList, 'method');
            if (!empty($arMethodList) && is_array($arMethodList)) {
                foreach ($arMethodList as $sMethodName) {
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

                    $obItem = call_user_func_array([$obItem, $sMethodName], $arParamList);
                }
            }

            return $obItem;
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
            $sFieldName . '_url'         => [
                'type'    => Type::string(),
                'resolve' => function ($obItem) use ($sFieldName) {
                    return ($obItem->$sFieldName) ? $obItem->$sFieldName->getPath() : null;
                }
            ],
            $sFieldName . '_title'       => [
                'type'    => Type::string(),
                'resolve' => function ($obItem) use ($sFieldName) {
                    return ($obItem->$sFieldName) ? $obItem->$sFieldName->attributes['title'] : null;
                },
            ],
            $sFieldName . '_description' => [
                'type'    => Type::string(),
                'resolve' => function ($obItem) use ($sFieldName) {
                    return ($obItem->$sFieldName) ? $obItem->$sFieldName->attributes['description'] : null;
                },
            ],
            $sFieldName . '_file_name'   => [
                'type'    => Type::string(),
                'resolve' => function ($obItem) use ($sFieldName) {
                    return ($obItem->$sFieldName) ? $obItem->$sFieldName->attributes['file_name']  : null;
                },
            ],
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
                "url"         => $obFile->getPath(),
                "title"       => Arr::get($obFile->attributes, 'title'),
                "description" => Arr::get($obFile->attributes, 'description'),
                "file_name"   => Arr::get($obFile->attributes, 'file_name'),
            ];

            $arFileList[] = $arFileData;
        }

        return $arFileList;
    }
}
