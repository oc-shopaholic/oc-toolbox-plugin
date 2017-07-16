<?php namespace Lovata\Toolbox\Traits\Item;

/**
 * Class TraitCheckItemTrashed
 * @package Lovata\Toolbox\Traits\Item
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @property bool $trashed
 */
trait TraitCheckItemTrashed
{
    protected $bWithTrashed = true;

    /**
     * Set checking element active flag
     * @param $bWithTrashed
     */
    public function withTrashed($bWithTrashed)
    {
        $this->bWithTrashed = (bool) $bWithTrashed;
    }

    /**
     * Check element trashed flag
     * @return bool
     */
    protected function isTrashed()
    {
        return !((!$this->bWithTrashed && $this->trashed) || $this->bWithTrashed);
    }
}