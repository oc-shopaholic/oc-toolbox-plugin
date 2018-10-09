<?php namespace Lovata\Toolbox\Classes\Event;

/**
 * Class AbstractBackendColumnHandler
 * @package Lovata\Toolbox\Classes\Event
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractBackendColumnHandler
{
    protected $iPriority = 1000;

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        $obEvent->listen('backend.list.extendColumns', function ($obWidget) {

            $sControllerClass = $this->getControllerClass();
            $sModelName = $this->getModelClass();

            /** @var \Backend\Widgets\Lists $obWidget */
            if (!$obWidget->getController() instanceof $sControllerClass) {
                return;
            }

            if (!$obWidget->model instanceof $sModelName) {
                return;
            }

            $this->extendColumns($obWidget);
        }, $this->iPriority);
    }

    /**
     * Extend backend columns
     * @param \Backend\Widgets\Lists $obWidget
     */
    abstract protected function extendColumns($obWidget);

    /**
     * Get model class name
     * @return string
     */
    abstract protected function getModelClass() : string;

    /**
     * Get controller class name
     * @return string
     */
    abstract protected function getControllerClass() : string;
}
