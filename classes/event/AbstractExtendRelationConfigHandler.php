<?php namespace Lovata\Toolbox\Classes\Event;

/**
 * Class AbstractExtendRelationConfigHandler
 * @package Lovata\Toolbox\Classes\Event
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractExtendRelationConfigHandler
{
    /**
     * Add listeners
     */
    public function subscribe()
    {
        $sControllerClass = $this->getControllerClass();
        $sControllerClass::extend(function ($obController) {
            /** @var \Backend\Classes\Controller $obController */
            $this->extendConfig($obController);
        });
    }

    /**
     * Extend controller
     * @param \Backend\Classes\Controller $obController
     */
    protected function extendConfig($obController)
    {
        if (empty($obController->implement)) {
            $obController->implement = [];
        }

        //Extend controller
        if (!in_array('Backend.Behaviors.RelationController', $obController->implement) && !in_array('Backend\Behaviors\RelationController', $obController->implement)) {
            $obController->implement[] = 'Backend.Behaviors.RelationController';
        }

        if (!isset($obController->relationConfig)) {
            $obController->addDynamicProperty('relationConfig');
        }

        $obController->relationConfig = $obController->mergeConfig(
            $obController->relationConfig,
            $this->getConfigPath()
        );
    }

    /**
     * Get controller class name
     * @return string
     */
    abstract protected function getControllerClass() : string;

    /**
     * Get path to config file
     * @return string
     */
    abstract protected function getConfigPath() : string;
}
