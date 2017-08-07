<?php namespace Lovata\Toolbox\Classes\Component;

use Input;
use Request;
use Cms\Classes\ComponentBase;

/**
 * Class ElementData
 * @package Lovata\Toolbox\Classes\Component
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class ElementData extends ComponentBase
{
    protected $iElementID = null;

    /** @var  \Lovata\Toolbox\Classes\Item\ElementItem */
    protected $obElementItem;

    /**
     * Male new element item
     * @param int $iElementID
     * @return \Lovata\Toolbox\Classes\Item\ElementItem
     */
    protected abstract function makeItem($iElementID);

    /**
     * Ajax listener
     * @return array|null
     */
    public function onGetData()
    {
        $this->iElementID = Input::get('element_id');
        $obElementItem = $this->makeItem($this->iElementID);

        return $obElementItem->toArray();
    }

    /**
     * Ajax listener
     * @return string
     */
    public function onGetJSONData()
    {
        $this->iElementID = Input::get('element_id');
        $obElementItem = $this->makeItem($this->iElementID);

        return $obElementItem->toJSON();
    }

    /**
     * Ajax listener
     */
    public function onAjaxRequest()
    {
        $this->iElementID = Input::get('element_id');
    }

    /**
     * Get element item
     * @param int $iElementID
     * @return \Lovata\Toolbox\Classes\Item\ElementItem
     */
    public function get($iElementID = null)
    {
        if(Request::ajax() && empty($iElementID)) {
            $iElementID = $this->iElementID;
        }

        $obElementItem = $this->makeItem($iElementID);
        return $obElementItem;
    }
}
