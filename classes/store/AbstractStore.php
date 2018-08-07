<?php namespace Lovata\Toolbox\Classes\Store;

use October\Rain\Support\Traits\Singleton;

use Kharanenka\Helper\CCache;

/**
 * Class AbstractStore
 * @package Lovata\Toolbox\Classes\Store
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractStore
{
    use Singleton;

    /**
     * Get element ID list from database
     * @return array|null
     */
    abstract protected function getIDListFromDB() : array;

    /**
     * Get cache key
     * @return string
     */
    abstract protected function getCacheKey() : string;

    /**
     * Get element ID list from cache or database
     * @return array|null
     */
    protected function getIDList() : array
    {
        //Get element ID list from cache
        $arElementIDList = $this->getIDListFromCache();
        if (!empty($arElementIDList) && is_array($arElementIDList)) {
            return $arElementIDList;
        }

        $arElementIDList = $this->getIDListFromDB();
        $this->saveIDList($arElementIDList);

        return $arElementIDList;
    }

    /**
     * Get element ID list from array
     * @return array|null
     */
    protected function getIDListFromCache() : array
    {
        $arCacheTags = $this->getCacheTagList();
        $sCacheKey = $this->getCacheKey();

        $arElementIDList  = (array) CCache::get($arCacheTags, $sCacheKey);

        return $arElementIDList;
    }

    /**
     * Save element ID list in cache
     * @param array $arElementIDList
     */
    protected function saveIDList($arElementIDList)
    {
        $arCacheTags = $this->getCacheTagList();
        $sCacheKey = $this->getCacheKey();

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arElementIDList);
    }

    /**
     * Clear element ID list in cache
     */
    protected function clearIDList()
    {
        $arCacheTags = $this->getCacheTagList();
        $sCacheKey = $this->getCacheKey();

        CCache::clear($arCacheTags, $sCacheKey);
    }

    /**
     * Get array with cache tags
     * @return array
     */
    protected function getCacheTagList()
    {
        return [static::class];
    }
}
