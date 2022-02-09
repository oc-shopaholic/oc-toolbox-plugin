<?php namespace Lovata\Toolbox\Classes\Api\Item;

use Event;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

use Illuminate\Support\Arr;

use Lovata\Toolbox\Classes\Api\Type\AbstractApiType;

/**
 * Class AbstractItemType
 * @package Lovata\Toolbox\Classes\Api\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractItemType extends AbstractApiType
{
    const ITEM_CLASS = '';
    const EVENT_EXTEND_TYPE_FIELDS = '';

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
            'id' => Type::id(),
        ];

        return $arArgumentList;
    }

    /**
     * Get element item image
     * @param $obItem
     * @param $sFieldName
     * @return array|null
     */
    protected function getImage($obItem, $sFieldName): ?array
    {
        $obImage = $obItem->{$sFieldName};
        if (empty($obImage)) {
            return null;
        }

        return $this->getImageData($obImage);
    }

    /**
     * Get element item image list
     * @param $obItem
     * @param $sFieldName
     * @return array|null
     */
    protected function getImageList($obItem, $sFieldName): ?array
    {
        $obImageList = $obItem->{$sFieldName};

        if (empty($obImageList)) {
            return null;
        }

        $arImageList = [];

        foreach ($obImageList as $obImage) {
            $arImageList[] = $this->getImageData($obImage);
        }

        return $arImageList;
    }

    protected function getImageData($obImage): array
    {
        return [
            'url' => $obImage->getPath(),
            'attributes' => $obImage->attributes
        ];
    }

    /**
     * Extend api item type field list
     * @param $arFieldList
     * @return array
     */
    protected function extendFieldList($arFieldList): array
    {
        if (empty(static::EVENT_EXTEND_TYPE_FIELDS)) {
            return $arFieldList;
        }

        $arEventFieldList = Event::fire(static::EVENT_EXTEND_TYPE_FIELDS, [static::instance()]);
        if (empty($arEventFieldList)) {
            return $arFieldList;
        }

        foreach ($arEventFieldList as $arEventFields) {
            $arFieldList = array_merge($arFieldList, $arEventFields);
        }

        return $arFieldList;
    }
}
