<?php namespace Lovata\Toolbox\Classes\Event;

use Lovata\Toolbox\Classes\Store\AbstractStoreWithoutParam;
use Lovata\Toolbox\Classes\Store\AbstractStoreWithParam;

/**
 * Class ModelHandler
 * @package Lovata\Toolbox\Classes\Event
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class ModelHandler
{
    /** @var  \Model */
    protected $obElement;

    protected $obListStore;

    protected $sIdentifierField = 'id';

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        $sModelClass = $this->getModelClass();
        $sModelClass::extend(function ($obElement) {

            /** @var \Model $obElement */
            $obElement->bindEvent('model.afterCreate', function () use ($obElement) {
                $this->obElement = $obElement;
                $this->init();
                $this->afterCreate();
            });

            /** @var \Model $obElement */
            $obElement->bindEvent('model.afterSave', function () use ($obElement) {
                $this->obElement = $obElement;
                $this->init();
                $this->afterSave();
            });

            /** @var \Model $obElement */
            $obElement->bindEvent('model.afterDelete', function () use ($obElement) {
                $this->obElement = $obElement;
                $this->init();
                $this->afterDelete();
            });
        });
    }

    /**
     * Get model class name
     * @return string
     */
    abstract protected function getModelClass();

    /**
     * Get item class name
     * @return string
     */
    abstract protected function getItemClass();

    /**
     * Init store objects
     */
    protected function init() {}

    /**
     * After create event handler
     */
    protected function afterCreate() {}

    /**
     * After save event handler
     */
    protected function afterSave()
    {
        $this->clearItemCache();
    }

    /**
     * After delete event handler
     */
    protected function afterDelete()
    {
        $this->clearItemCache();
    }

    /**
     * Clear item cache
     */
    protected function clearItemCache()
    {
        $sItemClass = $this->getItemClass();
        $sField = $this->sIdentifierField;

        $sItemClass::clearCache($this->obElement->$sField);
    }

    /**
     * @param $sField
     * @param AbstractStoreWithParam|AbstractStoreWithoutParam $obListStore
     */
    protected function checkFieldChanges($sField, $obListStore)
    {
        if (empty($sField) || empty($obListStore))

        if ($this->obElement->$sField == $this->obElement->getOriginal($sField)) {
            return;
        }

        if ($obListStore instanceof AbstractStoreWithoutParam) {
            $obListStore->clear();
        } elseif ($obListStore instanceof AbstractStoreWithParam) {
            $obListStore->clear($this->obElement->$sField);
            $obListStore->clear($this->obElement->getOriginal($sField));
        }
    }

    /**
     * Check: field value was change
     * @param string $sField
     * @return bool
     */
    protected function isFieldChanged($sField)
    {
        if (empty($sField)) {
            return false;
        }

        if ($this->obElement->$sField == $this->obElement->getOriginal($sField)) {
            return false;
        }

        return true;
    }
}
