<?php namespace Lovata\Toolbox\Components;

use Cms\Classes\ComponentBase;
use Kharanenka\Helper\CCache;
use Lovata\Toolbox\Models\ExampleModel;
use Lovata\Toolbox\Plugin;

/**
 * Class FullElementList
 * @package Lovata\Toolbox\Components
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 */
class FullElementList extends ComponentBase {

    public function componentDetails() {
        return [
            'name'        => 'lovata.toolbox::lang.component.full_element_list',
            'description' => 'lovata.toolbox::lang.component.full_element_list_desc'
        ];
    }

    /**
     * Get element list
     * @return array|null
     */
    public function get() {

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, ExampleModel::CACHE_TAG_LIST];
        $sCacheKey = ExampleModel::CACHE_TAG_LIST;

        //Get element ID list
        $arElementIDList = CCache::get($arCacheTags, $sCacheKey);
        if(empty($arElementIDList)) {

            $arElementIDList = ExampleModel::lists('id');
            if(empty($arElementIDList)) {
                return null;
            }

            CCache::forever($arCacheTags, $sCacheKey, $arElementIDList);
        }

        $arResult = [];

        //Get element cached data
        foreach($arElementIDList as $iElementID) {

            $arElementData = ExampleModel::getCacheData($iElementID);
            if(empty($arElementData)) {
                continue;
            }

            $arResult[] = $arElementData;
        }

        return $arResult;
    }
}
