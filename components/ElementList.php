<?php namespace Lovata\Toolbox\Components;

use Input;
use Cms\Classes\ComponentBase;
use Lovata\Toolbox\Plugin;
use Kharanenka\Helper\CCache;
use Kharanenka\Helper\Pagination;
use Lovata\Toolbox\Models\ExampleModel;

/**
 * Class ElementList
 * @package Lovata\Toolbox\Components
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ElementList extends ComponentBase
{
    protected $iElementOnPage = 10;
    protected $arResult = [];

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'lovata.toolbox::lang.component.element_list',
            'description' => 'lovata.toolbox::lang.component.element_list_desc'
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        $arProperties = [];
        $arProperties = array_merge($arProperties, Pagination::getProperties(Plugin::NAME));
        return $arProperties;
    }

    public function onRun()
    {
        $this->initData();
    }

    /**
     * Init start data
     */
    protected function initData()
    {
        $iElementOnPage = $this->property('count_per_page');
        if($iElementOnPage > 0) {
            $this->iElementOnPage = $iElementOnPage;
        }
    }

    /**
     * Get element list by page number
     * @param int $iPage
     * @return array
     */
    public function get($iPage = 1)
    {
        $arResult = [
            'list' => [],
            'pagination' => [],
            'page' => $iPage,
            'count' => 0,
        ];

        $iRequestPage = (int) Input::get('page');
        if(!empty($iRequestPage)) {
            $iPage = $iRequestPage;
        }

        //Set page default value
        if($iPage < 1) {
            $iPage = 1;
        }

        if(isset($this->arResult[$iPage])) {
            return $this->arResult[$iPage];
        }

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, ExampleModel::CACHE_TAG_LIST];
        $sCacheKey = $iPage;

        //Get element ID list
        $arElementIDList = CCache::get($arCacheTags, $sCacheKey);
        if(empty($arElementIDList)) {

            $arElementIDList = ExampleModel::lists('id');
            if(empty($arElementIDList)) {
                return $arResult;
            }

            CCache::forever($arCacheTags, $sCacheKey, $arElementIDList);
        }

        //Apply pagination
        $arResult['count'] = count($arElementIDList);
        $arResult['page'] = $iPage;
        $arResult['pagination'] = Pagination::get($iPage, $arResult['count'], $this->properties);

        //Get element ID list for page
        $arElementIDList = array_slice($arElementIDList, $this->iElementOnPage * ($iPage - 1), $this->iElementOnPage);

        //Get elements data
        foreach($arElementIDList as $iElementID) {

            $arElementData = ExampleModel::getCacheData($iElementID);
            if(!empty($arElementData)) {
                $arResult['list'][$iElementID] = $arElementData;
            }
        }

        $this->arResult[$iPage] = $arResult;
        return $arResult;
    }

    /**
     * Get ajax element list
     * @return string
     */
    public function onAjaxRequest()
    {
        $this->initData();
        return;
    }

    /**
     * Get pagination data
     * @param int $iPage
     * @return array|mixed
     */
    public function getPagination($iPage = 1)
    {
        $arResult = $this->get($iPage);
        if(isset($arResult['pagination'])) {
            return $arResult['pagination'];
        }

        return [];
    }

    /**
     * Get count elements
     * @param int $iPage
     * @return array|mixed
     */
    public function getCount($iPage = 1)
    {
        $arResult = $this->get($iPage);
        if(isset($arResult['count'])) {
            return $arResult['count'];
        }

        return 0;
    }
}
