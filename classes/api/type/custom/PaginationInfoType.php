<?php namespace Lovata\Toolbox\Classes\Api\Type\Custom;

use GraphQL\Type\Definition\Type;
use Lovata\Toolbox\Classes\Api\Type\AbstractObjectType;

/**
 * Class PaginationInfoType
 * @package Lovata\Toolbox\Classes\Api\Type\Custom
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class PaginationInfoType extends AbstractObjectType
{
    const TYPE_ALIAS = 'PaginationInfo';

    /** @var PaginationInfoType */
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
            ],
            'perPage' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Number of items per page',
            ],
            'totalPages' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Total number of pages',
            ],
            'totalItems' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Total number of items',
            ],
            'hasNextPage' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'When paginating forwards, are there more items?',
            ],
            'hasPreviousPage' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'When paginating backwards, are there more items?',
            ],
        ];

        return $arFieldList;
    }
}
