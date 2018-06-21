<?php namespace Lovata\Toolbox\Classes\Store;

use October\Rain\Support\Traits\Singleton;

/**
 * Class AbstractListStore
 * @package Lovata\Toolbox\Classes\Store
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractListStore
{
    use Singleton;

    protected $arStoreList = [];

    /**
     * Get store object from list
     * @param string $sFieldName
     * @return mixed|null
     */
    public function __get($sFieldName)
    {
        if (isset($this->arStoreList[$sFieldName])) {
            return $this->arStoreList[$sFieldName];
        }

        return null;
    }

    /**
     * Add store class to list and get store object
     * @param string $sFieldName
     * @param string $sClassName
     */
    protected function addToStoreList($sFieldName, $sClassName)
    {
        if (empty($sFieldName) || empty($sClassName) || !class_exists($sClassName)) {
            return;
        }

        $this->arStoreList[$sFieldName] = $sClassName::instance();
    }
}
