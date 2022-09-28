<?php namespace Lovata\Toolbox\Classes\Api\Event;

use Lovata\Toolbox\Classes\Api\Type\AbstractApiType;

/**
 * Class AbstractApiPermissionHandler
 * @package Lovata\Toolbox\Classes\Api\Event
 */
abstract class AbstractApiPermissionHandler
{
    protected $iPriority = 1000;

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        $obEvent->listen(AbstractApiType::EVENT_EXTEND_PERMISSION_LIST, function ($obApiType) {

            $sApiTypeClass = $this->getApiTypeClass();

            /** @var \Lovata\Toolbox\Classes\Api\Type\AbstractApiType $obApiType */
            if (!$obApiType instanceof $sApiTypeClass) {
                return;
            }

            $this->extendPermissions($obApiType);
        }, $this->iPriority);
    }

    /**
     * Extend api type fields
     * @param \Lovata\Toolbox\Classes\Api\Type\AbstractApiType $obApiType
     */
    abstract protected function extendPermissions(AbstractApiType $obApiType);

    /**
     * Get api type class name
     * @return string
     */
    abstract protected function getApiTypeClass(): string;
}
