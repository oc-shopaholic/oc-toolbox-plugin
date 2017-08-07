<?php namespace Lovata\Toolbox\Traits\Collection;

/**
 * Class TraitCheckItemActive
 * @package Lovata\Toolbox\Traits\Collection
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
}