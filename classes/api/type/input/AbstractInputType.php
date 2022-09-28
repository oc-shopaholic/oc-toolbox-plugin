<?php namespace Lovata\Toolbox\Classes\Api\Type\Input;

use Lovata\Toolbox\Classes\Api\Type\AbstractObjectType;
use Lovata\Toolbox\Classes\Api\Type\TypeList;

/**
 * Class AbstractInputType
 * @package Lovata\Toolbox\Classes\Api\Type
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
abstract class AbstractInputType extends AbstractObjectType
{
    const TYPE_ALIAS = '';
    const TYPE = TypeList::INPUT_TYPE;

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
        ];

        return $arTypeConfig;
    }
}
