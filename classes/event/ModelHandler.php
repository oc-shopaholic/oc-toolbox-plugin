<?php namespace Lovata\Toolbox\Classes\Event;

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
                $this->afterCreate();
            });

            /** @var \Model $obElement */
            $obElement->bindEvent('model.afterSave', function () use ($obElement) {
                $this->obElement = $obElement;
                $this->afterSave();
            });

            /** @var \Model $obElement */
            $obElement->bindEvent('model.afterDelete', function () use ($obElement) {
                $this->obElement = $obElement;
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
     * After create event handler
     */
    protected function afterCreate() {}

    /**
     * After save event handler
     */
    protected function afterSave()
    {
        $sModelClass = $this->getModelClass();
        if (empty($this->obElement) || !$this->obElement instanceof $sModelClass) {
            return;
        }

        $this->clearItemCache();
        $this->checkActiveField();
    }

    /**
     * After delete event handler
     */
    protected function afterDelete()
    {
        $sModelClass = $this->getModelClass();
        if (empty($this->obElement) || !$this->obElement instanceof $sModelClass) {
            return;
        }

        $this->clearItemCache();

        if ($this->obElement->active) {
            $this->clearActiveList();
        }
    }

    /**
     * Clear item cache
     */
    protected function clearItemCache()
    {
        $sItemClass = $this->getItemClass();
        $sItemClass::clearCache($this->obElement->id);
    }

    /**
     * Check brand "active" field, if it was changed, then clear cache
     */
    protected function checkActiveField()
    {
        //check product "active" field
        if ($this->obElement->getOriginal('active') == $this->obElement->active) {
            return;
        }

        $this->clearActiveList();
    }

    /**
     * Clear active list
     */
    protected function clearActiveList()
    {
        if (empty($this->obListStore) || !method_exists($this->obListStore, 'clearActiveList')) {
            return;
        }

        $this->obListStore->clearActiveList();
    }
}
