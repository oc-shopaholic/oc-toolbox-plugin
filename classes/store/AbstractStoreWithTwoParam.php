<?php namespace Lovata\Toolbox\Classes\Store;

/**
 * Class AbstractStoreWithTwoParam
 * @package Lovata\Toolbox\Classes\Store
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractStoreWithTwoParam extends AbstractStore
{
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
    public function get($sFilterValue, $sAdditionalParam = null)
    {
        if (empty($sFilterValue)) {
            return null;
        }

        $this->sValue = $sFilterValue;
        $this->sAdditionParam = $sAdditionalParam;

        $arElementIDList = $this->getIDList();

        return $arElementIDList;
    }

    /**
     * Get element ID list from database, without cache
     * @param mixed $sFilterValue
     * @param mixed $sAdditionalParam
     * @return array|null
     */
    public function getNoCache($sFilterValue, $sAdditionalParam = null)
    {
        if (empty($sFilterValue)) {
            return null;
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
        if (empty($sFilterValue)) {
            return;
        }

        $this->sValue = $sFilterValue;
        $this->sAdditionParam = $sAdditionalParam;

        $this->clearIDList();
    }

    /**
     * Get cache key
     * @return string
     */
    protected function getCacheKey()
    {
        return $this->sValue.'_'.$this->sAdditionParam;
    }
}
