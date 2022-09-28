<?php namespace Lovata\Toolbox\Classes\Api\Type\Custom;

use Lovata\Toolbox\Classes\Api\Type\AbstractObjectType;
use Lovata\Toolbox\Classes\Api\Type\Interfaces\FileInterfaceType;

/**
 * Class FileType
 * @package Lovata\Toolbox\Classes\Api\Type\Object
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class FileType extends AbstractObjectType
{
    const TYPE_ALIAS = 'File';

    /** @var FileType */
    protected static $instance;

    /**
     * @inheritDoc
     * @throws \GraphQL\Error\Error
     */
    protected function getInterfaceList(): array
    {
        return [
            $this->getRelationType(FileInterfaceType::TYPE_ALIAS),
        ];
    }

    /**
     * @inheritDoc
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $obFileInterfaceType = $this->getRelationType(FileInterfaceType::TYPE_ALIAS);

        return $obFileInterfaceType->getFields();
    }
}
