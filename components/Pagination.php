<?php namespace Lovata\Toolbox\Components;

use Input;
use Lovata\Toolbox\Plugin;
use Cms\Classes\ComponentBase;
use Kharanenka\Helper\Pagination as PaginationHelper;

/**
 * Class Pagination
 * @package Lovata\Toolbox\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Pagination extends ComponentBase
{
    /** @var int Element count on page */
    protected $iElementOnPage = 10;

    /** @var array Component property list */
    protected $arPropertyList = [];

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'lovata.toolbox::lang.component.pagination',
            'description' => 'lovata.toolbox::lang.component.pagination_desc',
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        $this->arPropertyList = array_merge($this->arPropertyList, PaginationHelper::getProperties(Plugin::NAME));

        return $this->arPropertyList;
    }

    /**
     * Init start data
     */
    public function init()
    {
        $iRequestElementOnPage = $this->property('count_per_page');
        if ($iRequestElementOnPage > 0) {
            $this->iElementOnPage = $iRequestElementOnPage;
        }
    }

    /**
     * Get page from request
     * @return int
     */
    public function getPageFromRequest()
    {
        $iPage = (int) trim(Input::get('page'));

        //Check page value
        if ($iPage < 1) {
            $iPage = 1;
        }

        return $iPage;
    }

    /**
     * Get count element per page
     * @return int
     */
    public function getCountPerPage()
    {
        return $this->iElementOnPage;
    }

    /**
     * Get max page value
     * @param int $iCount
     * @return int
     */
    public function getMaxPage($iCount)
    {
        if ($iCount < 1) {
            return 0;
        }

        return ceil($iCount / $this->iElementOnPage);
    }

    /**
     * Get element count for next page
     * @param int $iPage
     * @param int $iCount
     *
     * @return int
     */
    public function getCountForNextPage($iPage, $iCount)
    {
        if ($iCount < 1) {
            return 0;
        }

        $iPage = (int) trim($iPage);

        //Check page value
        if ($iPage < 1) {
            $iPage = 1;
        }

        //Get max page value
        $iMaxPage = $this->getMaxPage($iCount);
        if ($iMaxPage <= $iPage) {
            return 0;
        }

        $iNextCount = $iCount - $this->iElementOnPage * $iPage;
        if ($iNextCount > $this->iElementOnPage) {
            $iNextCount = $this->iElementOnPage;
        }

        return $iNextCount;
    }

    /**
     * Get pagination data
     * @param int $iPage
     * @param int $iCount
     * @return array|null
     */
    public function get($iPage, $iCount)
    {
        if ($iCount < 1) {
            return null;
        }

        $iPage = (int) trim($iPage);

        //Check page value
        if ($iPage < 1) {
            $iPage = 1;
        }

        return PaginationHelper::get($iPage, $iCount, $this->properties);
    }
}
