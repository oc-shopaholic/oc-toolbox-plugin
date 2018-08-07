<?php namespace Lovata\Toolbox\Classes\Store;

/**
 * Class AbstractStore
 * @package Lovata\Toolbox\Classes\Store
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractStoreWithoutParam extends AbstractStore
{
    /** @var null|array  */
    protected $arCachedList = null;

    /**
     * Get element ID list from cache or database
     * @return array|null
     */
    public function get() : array
    {
        if ($this->arCachedList !== null && is_array($this->arCachedList)) {
            return $this->arCachedList;
        }

        $arElementIDList = $this->getIDList();
        $this->arCachedList = $arElementIDList;

        return $arElementIDList;
    }

    /**
     * Get element ID list from database, without cache
     * @return array|null
     */
    public function getNoCache() : array
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
        $this->arCachedList = null;
    }

    /**
     * Get cache key
     * @return string
     */
    protected function getCacheKey() : string
    {
        return static::class;
    }
}
