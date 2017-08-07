<?php namespace Lovata\Toolbox\Classes\Item;

use Model;
use Lovata\Toolbox\Plugin;

/**
 * Class TestItem
 * @package Lovata\Toolbox\Classes\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @param int $id
 */
class TestItem extends ElementItem
{
    const CACHE_TAG_ELEMENT = 'toolbox-test-element';

    /** @var Model */
    protected $obElement = null;

    /**
     * Set element object
     */
    protected function setElementObject()
    {
        if(!empty($this->obElement) || empty($this->iElementID)) {
            return;
        }

        $obElement = new Model();
        $obElement->id = $this->iElementID;

        $this->obElement = $obElement;
    }

    /**
     * Get cache tag array for model
     * @return array
     */
    protected static function getCacheTag()
    {
        return [Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT];
    }

    /**
     * Set brand data from model object
     *
     * @return array
     */
    protected function getElementData()
    {
        if(empty($this->obElement)) {
            return null;
        }

        $arResult = [
            'id' => $this->obElement->id,
        ];

        return $arResult;
    }
}