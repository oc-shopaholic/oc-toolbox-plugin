<?php namespace Lovata\Toolbox\Traits\Tests;

/**
 * Class TestModelCacheTagConst
 * @package Lovata\Toolbox\Traits\Tests
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
trait TestModelCacheTagConst
{
    /**
     * Check model constant for cache tag
     */
    public function testCheckCacheConst()
    {
        $sModelClass = self::MODEL_NAME;

        $sCacheTagElement = $sModelClass::CACHE_TAG_ELEMENT;
        $this->assertNotEmpty($sCacheTagElement, $sModelClass.' model has empty CACHE_TAG_ELEMENT const');

        $sCacheTagList = $sModelClass::CACHE_TAG_LIST;
        $this->assertNotEmpty($sCacheTagList, $sModelClass.' model has empty CACHE_TAG_LIST const');
    }
}