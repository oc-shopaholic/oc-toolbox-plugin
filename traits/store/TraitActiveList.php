<?php namespace Lovata\Toolbox\Traits\Store;

use Kharanenka\Helper\CCache;
use Lovata\Toolbox\Plugin;

/**
 * Trait TraitActiveList
 * @package Lovata\Toolbox\Traits\Store
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @method array getActiveIDList()
 */
trait TraitActiveList
{
    /**
     * Get active element ID list
     * @return array|null
     */
    public function getActiveList()
    {
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, static::CACHE_TAG_LIST];
        $sCacheKey = static::CACHE_TAG_LIST;

        $arElementIDList = CCache::get($arCacheTags, $sCacheKey);
        if (!empty($arElementIDList)) {
            return $arElementIDList;
        }

        //Get sticker ID list
        /** @var array $arElementIDList */
        $arElementIDList = $this->getActiveIDList();
        if (empty($arElementIDList)) {
            return null;
        }

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arElementIDList);

        return $arElementIDList;
    }

    /**
     * Clear active list
     */
    public function clearActiveList()
    {
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, static::CACHE_TAG_LIST];
        $sCacheKey = static::CACHE_TAG_LIST;

        //Clear cache data
        CCache::clear($arCacheTags, $sCacheKey);
        $this->getActiveList();
    }
}
