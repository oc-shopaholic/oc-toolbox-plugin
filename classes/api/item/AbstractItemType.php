<?php namespace Lovata\Toolbox\Classes\Api\Item;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

use Lovata\Toolbox\Classes\Api\Type\AbstractApiType;

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
     * Get image fields
     * @param $sFieldName
     * @return array[]
     */
    protected function getImageFields($sFieldName): array
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
     * Get image list
     * @param $obItem
     * @param $sFieldName
     * @return array
     */
    protected function getImageList($obItem, $sFieldName): array
    {
        $obImages = $obItem->{$sFieldName};
        $arImages = [];

        if (empty($obImages)) {
            return $arImages;
        }

        foreach ($obImages as $obImage) {
            $arImageData = [
                "url"         => $obImage->getPath(),
                "title"       => Arr::get($obImage->attributes, 'title'),
                "description" => Arr::get($obImage->attributes, 'description'),
                "file_name"   => Arr::get($obImage->attributes, 'file_name'),
            ];

            $arImages[] = $arImageData;
        }

        return $arImages;
    }
}
