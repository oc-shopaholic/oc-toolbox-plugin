<?php namespace Lovata\Toolbox\Classes\Api\Event;

use Lovata\Toolbox\Classes\Api\Type\Enum\AbstractEnumType;

/**
 * Class AbstractApiEnumValuesHandler
 * @package Lovata\Toolbox\Classes\Api\Event
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
abstract class AbstractApiEnumValuesHandler extends AbstractApiTypeHandler
{
    protected $sApiTypeClass;

    /** @var array */
    private $arAddValueList = [];

    /** @var array */
    private $arRemoveValueList = [];

    /**
     * @throws \Exception
     */
    protected function validateApiTypeClass()
    {
        if (is_a($this->sApiTypeClass, AbstractEnumType::class, true)) {
            return;
        }

        throw new \Exception('Extended class must be a subclass off ' . AbstractEnumType::class);
    }

    protected function addEnumValues($arValueList)
    {
        $this->arAddValueList = $arValueList;
    }

    protected function removeEnumValues($arValueList)
    {
        $this->arRemoveValueList = $arValueList;
    }

    protected function runExtensionLogic($obApiType)
    {
        if (!empty($this->arAddValueList)) {
            $obApiType->addValues($this->arAddValueList);
        }

        if (!empty($this->arRemoveValueList)) {
            $obApiType->removeValues($this->arRemoveValueList);
        }
    }

    /**
     * Get api type class name
     * @return string
     */
    abstract protected function getApiTypeClass(): string;
}
