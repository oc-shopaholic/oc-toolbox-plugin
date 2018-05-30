<?php namespace Lovata\Toolbox\Classes\Store;

/**
 * Class AbstractStoreWithParam
 * @package Lovata\Toolbox\Classes\Store
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractStoreWithParam extends AbstractStore
{
    /** @var mixed */
    protected $sValue;

    /**
     * Get element ID list from cache or database
     * @param mixed $sFilterValue
     * @return array|null
     */
    public function get($sFilterValue)
    {
        if (empty($sFilterValue)) {
            return null;
        }

        $this->sValue = $sFilterValue;

        $arElementIDList = $this->getIDList();

        return $arElementIDList;
    }

    /**
     * Get element ID list from database, without cache
     * @param mixed $sFilterValue
     * @return array|null
     */
    public function getNoCache($sFilterValue)
    {
        if (empty($sFilterValue)) {
            return null;
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
    }

    /**
     * Get cache key
     * @return string
     */
    protected function getCacheKey()
    {
        return $this->sValue;
    }
}
