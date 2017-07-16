<?php namespace Lovata\Toolbox\Traits\Item;

/**
 * Class TraitCheckItemActive
 * @package Lovata\Toolbox\Traits\Item
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @property bool $active
 */
trait TraitCheckItemActive
{
    protected $bCheckActive = true;

    /**
     * Set checking element active flag
     * @param $bCheckActive
     */
    public function setCheckingActive($bCheckActive)
    {
        $this->bCheckActive = (bool) $bCheckActive;
    }

    /**
     * Check element active flag
     * @return bool
     */
    protected function isActive()
    {
        return ($this->bCheckActive && $this->getAttribute('active') || !$this->bCheckActive);
    }
}