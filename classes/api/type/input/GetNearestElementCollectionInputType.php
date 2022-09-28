<?php namespace Lovata\Toolbox\Classes\Api\Type\Input;

use GraphQL\Type\Definition\Type;

/**
 * Class GetNearestElementCollectionInputType
 * @package Lovata\Toolbox\Classes\Api\Type\Input
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class GetNearestElementCollectionInputType extends AbstractInputType
{
    const TYPE_ALIAS = 'GetNearestElementCollectionInput';

    /** @var GetNearestElementCollectionInputType */
    protected static $instance;

    /**
     * @inheritDoc
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'elementId' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Element ID starts from',
            ],
            'count' => [
                'type' => Type::int(),
                'description' => 'How many items are in the resulting collection',
                'defaultValue' => 1,
            ],
            'cyclic' => [
                'type' => Type::boolean(),
                'description' => 'Start traversing the elements of the collection from the beginning if the last element is reached.',
                'defaultValue' => false,
            ],

        ];

        return $arFieldList;
    }

    protected function getDescription(): string
    {
        return 'Method returns new collection with previous nearest elements.';
    }
}
