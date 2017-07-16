<?php namespace Lovata\Toolbox\Traits\Collection;

/**
 * Class TraitCheckItemTrashed
 * @package Lovata\Toolbox\Traits\Collection
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @property bool $trashed
 */
trait TraitCheckItemTrashed
{
    protected $bCheckTrashed = true;

    /**
     * Set checking element active flag
     * @param $bCheckTrashed
     */
    public function withTrashed($bCheckTrashed)
    {
        $this->bCheckTrashed = (bool) $bCheckTrashed;
    }
}