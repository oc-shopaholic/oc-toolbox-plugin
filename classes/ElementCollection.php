<?php namespace Lovata\Toolbox\Classes;

use App;
use Lovata\Toolbox\Traits\Helpers\TraitClassExtension;

/**
 * Class ElementCollection
 * @package Lovata\Toolbox\Classes
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class ElementCollection
{
    use TraitClassExtension;

    /** @var array */
    protected $arElementIDList = null;

    /**
     * Make new list store
     * @param $arElementIDList
     *
     * @return $this
     */
    public static function make($arElementIDList = [])
    {
        /** @var ElementCollection $obStore */
        $obStore = App::make(static::class);

        if(!empty($arElementIDList) && is_array($arElementIDList)) {
            $obStore->arElementIDList = $arElementIDList;
        }

        return $obStore;
    }

    /**
     * Check list is empty
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->arElementIDList);
    }

    /**
     * Get element ID list
     * @return array
     */
    public function getIDList()
    {
        return $this->arElementIDList;
    }

    /**
     * Set clear array to element list
     * @return $this
     */
    public function clear()
    {
        $this->arElementIDList = [];
        return $this;
    }

    /**
     * Get element count
     * @return int
     */
    public function count()
    {
        if(empty($this->arElementIDList)) {
            return 0;
        }

        return count($this->arElementIDList);
    }

    /**
     * Apply array_intersect for element array list
     * @param array $arElementIDList
     * @return $this
     */
    public function intersect($arElementIDList)
    {
        if(empty($arElementIDList)) {
            return $this->clear();
        }

        if($this->arElementIDList === null) {
            $this->arElementIDList = $arElementIDList;
            return $this;
        }

        if(empty($this->arElementIDList)) {
            return $this;
        }

        $this->arElementIDList = array_intersect($this->arElementIDList, $arElementIDList);
        return $this;
    }

    /**
     * Apply pagination for list
     * @param int $iPage
     * @param int $iElementOnPage
     * @return $this
     */
    public function pagination($iPage, $iElementOnPage)
    {
        if(empty($this->arElementIDList)) {
            return $this;
        }

        if($iPage < 1) {
            $iPage = 1;
        }

        if($iElementOnPage < 1) {
            $iElementOnPage = 1;
        }

        $this->arElementIDList = array_slice($this->arElementIDList, $iElementOnPage * ($iPage - 1), $iElementOnPage);
        return $this;
    }
}