<?php namespace Lovata\Toolbox\Classes\Api\Type\Input;

use GraphQL\Type\Definition\Type;

/**
 * Class CollectionFilterInputType
 * @package Lovata\Toolbox\Classes\Api\Type\Input
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class FilterCollectionInputType extends AbstractInputType
{
    const TYPE_ALIAS = 'FilterCollectionInput';

    /** @var FilterCollectionInputType */
    protected static $instance;

    /**
     * Get field list
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'diff'             => [
                'type'        => Type::listOf(Type::id()),
                'description' => 'Method applies array_diff() function to collection and array of element IDs $arElementIDList.',
            ],
            'exclude'          => [
                'type'        => Type::id(),
                'description' => 'Method excludes element with ID = $iElementID from collection.',
            ],
            'filterBySequence' => [
                'type'        => Type::listOf(Type::id()),
                'description' => 'Method applies array_intersect() function to array of element IDs $arElementIDList and collection.',
            ],
            'getNearestNext'   => [
                'type'        => $this->getRelationType(GetNearestElementCollectionInputType::TYPE_ALIAS),
                'description' => 'Method returns new collection with next nearest elements.',
            ],
            'getNearestPrev'   => [
                'type'        => $this->getRelationType(GetNearestElementCollectionInputType::TYPE_ALIAS),
                'description' => 'Method returns new collection with previous nearest elements.',
            ],
            'intersect'        => [
                'type'        => Type::listOf(Type::id()),
                'description' => 'Method applies array_intersect() function to collection and array of element IDs $arElementIDList.',
            ],
            'random'           => [
                'type'        => Type::int(),
                'description' => 'Method returns array of random ElementItem objects.',
            ],
        ];

        return array_merge($this->getFilterConfig(), $arFieldList);
    }

    /**
     * Get filter config
     * @return array
     */
    protected function getFilterConfig(): array
    {
        return [];
    }

    /**
     * Get description
     * @return string
     */
    protected function getDescription(): string
    {
        return 'Filter ElementCollection input.';
    }
}
