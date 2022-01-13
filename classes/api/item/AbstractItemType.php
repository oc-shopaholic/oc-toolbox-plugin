<?php namespace Lovata\Toolbox\Classes\Api\Item;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

use Lovata\Toolbox\Classes\Api\Type\AbstractApiType;
use Lovata\Toolbox\Classes\Api\Type\TypeFactory;

use Arr;

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

            return $obItem;
        };
    }

    /**
     * Returns element item by ID
     * @param int|string $iElementID
     * @return \App\Classes\Item\AbstractElementItem
     */
    protected function findElement($iElementID)
    {
        if (empty($iElementID)) {
            return null;
        }

        $sItemClass = static::ITEM_CLASS;
        /** @var \App\Classes\Item\AbstractElementItem $obItem */
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
}
