<?php namespace Lovata\Toolbox\Classes\Component;

use Input;
use Cms\Classes\ComponentBase;

/**
 * Class ElementData
 * @package Lovata\Toolbox\Classes\Component
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class ElementData extends ComponentBase
{
    /**
     * Ajax listener
     * @return array|null
     */
    public function onGetData()
    {
        $iElementID = Input::get('element_id');
        $obElementItem = $this->makeItem($iElementID);

        return $obElementItem->toArray();
    }

    /**
     * Ajax listener
     * @return string
     */
    public function onGetJSONData()
    {
        $iElementID = Input::get('element_id');
        $obElementItem = $this->makeItem($iElementID);

        return $obElementItem->toJSON();
    }

    /**
     * Ajax listener
     * @return bool
     */
    public function onAjaxRequest()
    {
        return true;
    }

    /**
     * Get element item
     * @param int $iElementID
     * @return \Lovata\Toolbox\Classes\Item\ElementItem
     */
    public function get($iElementID)
    {
        $obElementItem = $this->makeItem($iElementID);

        return $obElementItem;
    }

    /**
     * Male new element item
     * @param int $iElementID
     * @return \Lovata\Toolbox\Classes\Item\ElementItem
     */
    abstract protected function makeItem($iElementID);
}
