<?php namespace Lovata\Toolbox\Classes\Api\Type;

/**
 * Class AbstractEnumType
 * @package Lovata\Toolbox\Classes\Api\Type
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
abstract class AbstractEnumType extends AbstractApiType
{
    const TYPE_ALIAS = '';
    const IS_ENUM_TYPE = true;

    protected $arValueList = [];

    /**
     * Get value list for config
     * @return array
     */
    abstract protected function getValueList(): array;

    /**
     * Get type config
     * @return array
     */
    protected function getTypeConfig(): array
    {
        $arTypeConfig = [
            'name'        => static::TYPE_ALIAS,
            'values'      => $this->arValueList,
            'description' => $this->sDescription,
        ];

        return $arTypeConfig;
    }

    protected function init()
    {
        $this->arValueList = $this->getValueList();

        parent::init();
    }

    protected function getFieldList(): array
    {
        return [];
    }
}
