<?php namespace Lovata\Toolbox\Classes\Event;

/**
 * Class AbstractBackendFieldHandler
 * @package Lovata\Toolbox\Classes\Event
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractBackendFieldHandler
{
    protected $iPriority = 1000;

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        $obEvent->listen('backend.form.extendFields', function ($obWidget) {

            $sControllerClass = $this->getControllerClass();
            $sModelName = $this->getModelClass();

            /** @var \Backend\Widgets\Form $obWidget */
            if (!$obWidget->getController() instanceof $sControllerClass || $obWidget->isNested || empty($obWidget->context)) {
                return;
            }

            if (!$obWidget->model instanceof $sModelName) {
                return;
            }

            $this->extendFields($obWidget);
        }, $this->iPriority);
    }

    /**
     * Extend backend fields
     * @param \Backend\Widgets\Form $obWidget
     */
    abstract protected function extendFields($obWidget);

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
