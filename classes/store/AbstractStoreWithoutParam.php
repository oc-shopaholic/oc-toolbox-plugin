<?php namespace Lovata\Toolbox\Classes\Store;

/**
 * Class AbstractStore
 * @package Lovata\Toolbox\Classes\Store
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractStoreWithoutParam extends AbstractStore
{
    /**
     * Get element ID list from cache or database
     * @return array|null
     */
    public function get()
    {
        $arElementIDList = $this->getIDList();

        return $arElementIDList;
    }

    /**
     * Get element ID list from database, without cache
     * @return array|null
     */
    public function getNoCache()
    {
        $arElementIDList = $this->getIDListFromDB();

        return $arElementIDList;
    }

    /**
     * Clear element ID list
     */
    public function clear()
    {
        $this->clearIDList();
    }

    /**
     * Get cache key
     * @return string
     */
    protected function getCacheKey()
    {
        return static::class;
    }
}
