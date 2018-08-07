<?php namespace Lovata\Toolbox\Classes\Store;

/**
 * Class AbstractStoreWithParam
 * @package Lovata\Toolbox\Classes\Store
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractStoreWithParam extends AbstractStore
{
    /** @var array  */
    protected $arCachedList = [];

    /** @var mixed */
    protected $sValue;

    /**
     * Get element ID list from cache or database
     * @param mixed $sFilterValue
     * @return array|null
     */
    public function get($sFilterValue) : array
    {
        if (empty($sFilterValue)) {
            return [];
        }

        $this->sValue = $sFilterValue;
        if (array_key_exists($this->getCacheKey(), $this->arCachedList) && is_array($this->arCachedList[$this->getCacheKey()])) {
            return $this->arCachedList[$this->getCacheKey()];
        }

        $arElementIDList = $this->getIDList();
        $this->arCachedList[$this->getCacheKey()] = $arElementIDList;

        return $arElementIDList;
    }

    /**
     * Get element ID list from database, without cache
     * @param mixed $sFilterValue
     * @return array|null
     */
    public function getNoCache($sFilterValue) : array
    {
        if (empty($sFilterValue)) {
            return [];
        }

        $this->sValue = $sFilterValue;
        $arElementIDList = $this->getIDListFromDB();

        return $arElementIDList;
    }

    /**
     * Clear element ID list
     * @param mixed $sFilterValue
     */
    public function clear($sFilterValue)
    {
        if (empty($sFilterValue)) {
            return;
        }

        $this->sValue = $sFilterValue;

        $this->clearIDList();

        if (array_key_exists($this->getCacheKey(), $this->arCachedList)) {
            unset($this->arCachedList[$this->getCacheKey()]);
        }
    }

    /**
     * Get cache key
     * @return string
     */
    protected function getCacheKey() : string
    {
        return $this->sValue;
    }
}
