<?php namespace Lovata\Toolbox\Classes\Component;

use Input;
use Cms\Classes\ComponentBase;

use Lovata\Toolbox\Plugin;
use Kharanenka\Helper\Pagination;

/**
 * Class ElementList
 * @package Lovata\Toolbox\Classes\Component
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class ElementList extends ComponentBase
{
    /** @var int Element count on page */
    protected $iElementOnPage = 10;

    /** @var  \Lovata\Toolbox\Classes\Collection\ElementCollection */
    protected $obItemCollection;

    /** @var array Component property list */
    protected $arPropertyList = [];

    /**
     * @return array
     */
    public function defineProperties()
    {
        $this->arPropertyList = array_merge($this->arPropertyList, Pagination::getProperties(Plugin::NAME));
        return $this->arPropertyList;
    }

    /**
     * Init start data
     */
    public function init()
    {
        $iElementOnPage = $this->property('count_per_page');
        if($iElementOnPage > 0) {
            $this->iElementOnPage = $iElementOnPage;
        }

        $this->obItemCollection = $this->makeCollection();
    }

    /**
     * Make new item collection
     * @return \Lovata\Toolbox\Classes\Collection\ElementCollection
     */
    protected abstract function makeCollection();

    /**
     * @param string $sName
     * @param array $arParamList
     * @return $this
     */
    public function __call($sName, $arParamList)
    {
        if(empty($this->obItemCollection) || empty($sName) || !method_exists($this->obItemCollection, $sName)) {
            return $this;
        }

        call_user_func_array($this->obItemCollection->$sName, $arParamList);
        return $this;
    }

    /**
     * Ajax listener
     * @return bool
     */
    public function obAjaxRequest()
    {
        return true;
    }

    /**
     * Get page from request
     * @return int
     */
    public function getPageFromRequest()
    {
        $iPage = (int) trim(Input::get('page'));

        //Check page value
        if($iPage < 1) {
            $iPage = 1;
        }

        return $iPage;
    }

    /**
     * Get max page value
     * @return int
     */
    public function getMaxPage()
    {
        if(empty($this->obItemCollection)) {
            return 0;
        }

        return ceil($this->obItemCollection->count() / $this->iElementOnPage);
    }

    /**
     * Get pagination data
     * @param int $iPage
     * @return array|null
     */
    public function getPagination($iPage)
    {
        if(empty($this->obItemCollection)) {
            return null;
        }

        $iPage = (int) trim($iPage);

        //Check page value
        if($iPage < 1) {
            $iPage = 1;
        }

        return Pagination::get($iPage, $this->obItemCollection->count(), $this->properties);
    }
}
