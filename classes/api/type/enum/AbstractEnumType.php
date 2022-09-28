<?php namespace Lovata\Toolbox\Classes\Api\Type\Enum;

use Lovata\Toolbox\Classes\Api\Type\AbstractApiType;
use Lovata\Toolbox\Classes\Api\Type\TypeList;

/**
 * Class AbstractEnumType
 * @package Lovata\Toolbox\Classes\Api\Type
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
abstract class AbstractEnumType extends AbstractApiType
{
    const TYPE = TypeList::ENUM_TYPE;
    const TYPE_ALIAS = '';

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

    /**
     * Add values
     * @param array $arValueList
     * @return void
     */
    public function addValues(array $arValueList)
    {
        $this->arValueList = array_merge($this->arValueList, $arValueList);
    }

    /**
     * Remove values
     * @param array $arValueList
     * @return void
     */
    public function removeValues(array $arValueList)
    {
        if (empty($arValueList)) {
            return;
        }

        foreach ($arValueList as $sKey) {
            unset($this->arValueList[$sKey]);
        }
    }

    protected function init()
    {
        $this->arValueList = $this->getValueList();
        parent::init();
    }
}
