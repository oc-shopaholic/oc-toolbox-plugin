<?php namespace Lovata\Toolbox\Traits\Helpers;

/**
 * Trait TraitCached
 * @package Lovata\Toolbox\Traits\Helpers
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @property array $cached
 */
trait TraitCached
{
    /**
     * Add fields to cached field list
     * @param array|string $arFieldList
     */
    public function addCachedField($arFieldList)
    {
        if (empty($arFieldList)) {
            return;
        }

        if (empty($this->cached) || !is_array($this->cached)) {
            $this->cached = [];
        }

        if (is_string($arFieldList)) {
            $arFieldList = [$arFieldList];
        }

        $this->cached = array_merge($this->cached, $arFieldList);
        $this->cached = array_unique($this->cached);
    }

    /**
     * Get cached field list
     * @return array
     */
    public function getCachedField(): array
    {
        if (empty($this->cached) || !is_array($this->cached)) {
            $this->cached = [];
        }

        return $this->cached;
    }
}
