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

        //An empty cached list is a valid value, do not query the database again
        if ($this->hasCachedEmptyIDList()) {
            return [];
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
     * Check if cache contains a valid empty ID list.
     * Only the raw cached value === [] counts as a hit. Child classes that
     * override getIDListFromCache() with "empty result = force rebuild"
     * semantics (for example time based invalidation) can override this
     * method and return false to keep the old behavior.
     * @return bool
     */
    protected function hasCachedEmptyIDList() : bool
    {
        $arCacheTags = $this->getCacheTagList();
        $sCacheKey = $this->getCacheKey();

        return CCache::get($arCacheTags, $sCacheKey) === [];
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
