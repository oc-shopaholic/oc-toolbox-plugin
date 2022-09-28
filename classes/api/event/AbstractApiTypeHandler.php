<?php namespace Lovata\Toolbox\Classes\Api\Event;

use Lovata\Toolbox\Classes\Api\Type\AbstractApiType;

/**
 * Class AbstractApiTypeHandler
 * @package Lovata\Toolbox\Classes\Api\Event
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
abstract class AbstractApiTypeHandler
{
    /** @var AbstractApiType */
    protected $sApiTypeClass;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->sApiTypeClass = $this->getApiTypeClass();
        $this->validateApiTypeClass();
        $this->subscribe();
        $this->extendApiType();
    }

    /**
     * Subscribe
     * @return mixed
     */
    abstract public function subscribe();

    /**
     * Validate ApiType
     * @throws \Exception
     */
    protected function validateApiTypeClass()
    {
        if (is_a($this->sApiTypeClass, AbstractApiType::class, true)) {
            return;
        }

        throw new \Exception('Extended class must be instance off ' . AbstractApiType::class);
    }

    /**
     * Run extension logic
     * @param $obApiType
     * @return mixed
     */
    abstract protected function runExtensionLogic($obApiType);

    /**
     * Get api type class name
     * @return string
     */
    abstract protected function getApiTypeClass(): string;

    /**
     * Extension body
     */
    private function extendApiType()
    {
        $this->sApiTypeClass::extend(function ($obApiType) {
            $this->runExtensionLogic($obApiType);
        });
    }
}
