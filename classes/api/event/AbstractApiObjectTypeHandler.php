<?php namespace Lovata\Toolbox\Classes\Api\Event;

use Lovata\Toolbox\Classes\Api\Type\AbstractObjectType;

/**
 * Class AbstractApiObjectTypeHandler
 * @package Lovata\Toolbox\Classes\Api\Event
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
abstract class AbstractApiObjectTypeHandler extends AbstractApiTypeHandler
{
    /** @var array */
    private $arAddFiledList = [];

    /** @var array */
    private $arRemoveFiledList = [];

    /** @var array */
    private $arAddArgumentList = [];

    /** @var array */
    private $arRemoveArgumentList = [];

    /**
     * @inheritDoc
     */
    abstract public function subscribe();

    /**
     * @inheritDoc
     */
    protected function validateApiTypeClass()
    {
        if (is_a($this->sApiTypeClass, AbstractObjectType::class, true)) {
            return;
        }

        throw new \Exception('Extended class must be a subclass off ' . AbstractObjectType::class);
    }

    protected function addFields($arFieldList)
    {
        $this->arAddFiledList = $arFieldList;
    }

    protected function removeFields($arFieldList)
    {
        $this->arRemoveFiledList = $arFieldList;
    }

    protected function addArguments($arArgumentList)
    {
        $this->arAddArgumentList = $arArgumentList;
    }

    protected function removeArguments($arArgumentList)
    {
        $this->arRemoveArgumentList = $arArgumentList;
    }

    /**
     * Run extension logic
     * @param $obApiType
     * @return void
     */
    protected function runExtensionLogic($obApiType)
    {
        /** @var AbstractObjectType $obApiType */
        // Add type fields
        if (!empty($this->arAddFiledList)) {
            $obApiType->addFields($this->arAddFiledList);
        }

        // Remove type fields
        if (!empty($this->arRemoveFiledList)) {
            $obApiType->removeFields($this->arRemoveFiledList);
        }

        // Add type arguments
        if (!empty($this->arAddArgumentList)) {
            $obApiType->addArguments($this->arAddArgumentList);
        }

        // Remove type arguments
        if (!empty($this->arRemoveArgumentList)) {
            $obApiType->removeArguments($this->arRemoveArgumentList);
        }
    }

    /**
     * @inheritDoc
     */
    abstract protected function getApiTypeClass(): string;
}
