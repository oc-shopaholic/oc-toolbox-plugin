<?php namespace Lovata\Toolbox\Classes\Collection;

use Lovata\Toolbox\Classes\Item\TestItem;

/**
 * Class TestCollection
 * @package Lovata\Toolbox\Classes\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class TestCollection extends ElementCollection
{
    /**
     * Make element item
     * @param int   $iElementID
     * @param \Lovata\Shopaholic\Models\Product  $obElement
     *
     * @return TestItem
     */
    protected function makeItem($iElementID, $obElement = null)
    {
        return TestItem::make($iElementID, $obElement);
    }
}