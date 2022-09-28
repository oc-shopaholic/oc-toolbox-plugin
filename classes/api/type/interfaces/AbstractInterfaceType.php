<?php namespace Lovata\Toolbox\Classes\Api\Type\Interfaces;

use GraphQL\Type\Definition\ObjectType;
use Lovata\Toolbox\Classes\Api\Type\AbstractApiType;
use Lovata\Toolbox\Classes\Api\Type\TypeList;

/**
 * Class AbstractInterfaceType
 * @package Lovata\Toolbox\Classes\Api\Type
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
abstract class AbstractInterfaceType extends AbstractApiType
{
    const ITEM_CLASS = '';
    const TYPE = TypeList::INTERFACE_TYPE;

    /**
     * Get type config
     * @return array
     */
    protected function getTypeConfig(): array
    {
        $arTypeConfig = [
            'name'        => static::TYPE_ALIAS,
            'fields'      => $this->arFieldList,
            'description' => $this->sDescription,
            'resolveType' => function ($obObjectType) {
                return $this->getResolveType($obObjectType);
            },
        ];

        return $arTypeConfig;
    }

    /**
     * Returns concrete interface implementor
     * @param $obObjectType
     * @return ObjectType
     */
    abstract protected function getResolveType($obObjectType): ObjectType;
}
