<?php namespace Lovata\Toolbox\Classes\Api\Type\Input;

use GraphQL\Type\Definition\Type;

/**
 * Class PaginateInputType
 * @package Lovata\Toolbox\Classes\Api\Type\Input
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class PaginateInputType extends AbstractInputType
{
    const TYPE_ALIAS = 'PaginateInput';
    const PAGE_DEFAULT_VALUE = 1;
    const PER_PAGE_DEFAULT_VALUE = 10;

    /** @var PaginateInputType */
    protected static $instance;

    /**
     * @inheritDoc
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'page' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Current page number',
                'defaultValue' => self::PAGE_DEFAULT_VALUE,
            ],
            'perPage' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Current page number',
                'defaultValue' => self::PER_PAGE_DEFAULT_VALUE,
            ],
        ];

        return $arFieldList;
    }
}
