<?php namespace Lovata\Toolbox\Classes\Api\Type\Payload;

use GraphQL\Type\Definition\Type;
use Lovata\Toolbox\Classes\Api\Type\AbstractObjectType;

/**
 * Class AbstractMutationPayloadType
 * @package Lovata\Toolbox\Classes\Api\Type
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class AbstractMutationPayloadType extends AbstractObjectType
{
    const TYPE_ALIAS = '';
    const RELATED_TYPE_ALIAS = '';

    /**
     * Get field list
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        return array_merge($this->getDefaultFieldList(), $this->getAdditionalFieldList());
    }

    /**
     * Get default mutation field list
     * @return array
     */
    protected function getDefaultFieldList(): array
    {
        return [
            'status' => [
                'type' => Type::boolean(),
                'description' => 'Operation status. Returns `true` if success.',
            ],
            'error' => [
                //TODO: implement error interface
                'type' => Type::string(),
                'description' => 'Operation error',
            ],
        ];
    }

    /**
     * Get additional field list
     * @return array[]
     * @throws \GraphQL\Error\Error
     */
    protected function getAdditionalFieldList(): array
    {
        $arFieldList = [
            'recordId' => [
                'type' => Type::id(),
                'description' => $this->getRecordIdFieldDescription(),
            ],
            'record' => [
                'type' => $this->getRelationType(static::RELATED_TYPE_ALIAS),
                'description' => $this->getRecordFieldDescription(),
            ]
        ];

        return $arFieldList;
    }

    /**
     * Get description for recordId field
     * @return string
     */
    protected function getRecordIdFieldDescription(): string
    {
        return 'Unique ID of the mutated record.';
    }

    /**
     * Get description for record field
     * @return string
     */
    protected function getRecordFieldDescription(): string
    {
        return 'Mutated record data';
    }
}
