<?php namespace Lovata\Toolbox\Classes\Store;

/**
 * Class AbstractStoreWithTwoParam
 * @package Lovata\Toolbox\Classes\Store
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractStoreWithTwoParam extends AbstractStore
{
    /** @var array */
    protected $arCachedList = [];

    /** @var mixed */
    protected $sValue;

    /** @var mixed */
    protected $sAdditionParam;

    /**
     * Get element ID list from cache or database
     * @param mixed $sFilterValue
     * @param mixed $sAdditionalParam
     * @return array|null
     */
    public function get($sFilterValue, $sAdditionalParam = null) : array
    {
        if (empty($sFilterValue) && $sFilterValue !== 0 && $sFilterValue !== '0') {
            return [];
        }

        $this->sValue = $sFilterValue;
        $this->sAdditionParam = $sAdditionalParam;
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
     * @param mixed $sAdditionalParam
     * @return array|null
     */
    public function getNoCache($sFilterValue, $sAdditionalParam = null) : array
    {
        if (empty($sFilterValue) && $sFilterValue !== 0 && $sFilterValue !== '0') {
            return [];
        }

        $this->sValue = $sFilterValue;
        $this->sAdditionParam = $sAdditionalParam;

        $arElementIDList = $this->getIDListFromDB();

        return $arElementIDList;
    }

    /**
     * Clear element ID list
     * @param mixed $sFilterValue
     * @param mixed $sAdditionalParam
     */
    public function clear($sFilterValue, $sAdditionalParam = null)
    {
        if (empty($sFilterValue) && $sFilterValue !== 0 && $sFilterValue !== '0') {
            return;
        }

        $this->sValue = $sFilterValue;
        $this->sAdditionParam = $sAdditionalParam;

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
        return $this->sValue.'_'.$this->sAdditionParam;
    }
}
