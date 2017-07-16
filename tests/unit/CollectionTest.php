<?php namespace Lovata\Shopaholic\Tests\Unit;

include_once __DIR__.'/../../../toolbox/vendor/autoload.php';
include_once __DIR__.'/../../../../../tests/PluginTestCase.php';

use Lovata\Toolbox\Classes\Collection\TestCollection;
use PluginTestCase;

/**
 * Class Collection
 * @package Lovata\Shopaholic\Tests\Unit
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class Collection extends PluginTestCase
{
    protected $arElementIDList = [1,2,3,4,5];
    protected $arIntersectIDList = [3,4,5,8];

    /**
     * Test make method in item collection class
     */
    public function testMakeMethod()
    {
        $sMessage = 'Error in "getIDList" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);
        self::assertEquals($this->arElementIDList, $obCollection->getIDList(), $sMessage);
    }

    /**
     * Test clear method in item collection class
     */
    public function testClearMethod()
    {
        $sMessage = 'Error in "clear" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);
        $obCollection->clear();

        self::assertEquals([], $obCollection->getIDList(), $sMessage);
    }

    /**
     * Test count method in item collection class
     */
    public function testCountMethod()
    {
        $sMessage = 'Error in "count" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);
        self::assertEquals(count($this->arElementIDList), $obCollection->count(), $sMessage);
    }

    /**
     * Test intersect method in item collection class
     */
    public function testIntersectMethod()
    {
        $sMessage = 'Error in "intersect" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);
        $obCollection->intersect($this->arIntersectIDList);

        $arResult = array_intersect($this->arElementIDList, $this->arIntersectIDList);

        self::assertEquals($arResult, $obCollection->getIDList(), $sMessage);
    }

    /**
     * Test merge method in item collection class
     */
    public function testMergeMethod()
    {
        $sMessage = 'Error in "merge" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);
        $obCollection->merge($this->arIntersectIDList);

        $arResult = array_merge($this->arElementIDList, $this->arIntersectIDList);
        $arResult = array_unique($arResult);

        self::assertEquals($arResult, $obCollection->getIDList(), $sMessage);
    }

    /**
     * Test all method in item collection class
     */
    public function testAllMethod()
    {
        $sMessage = 'Error in "all" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);

        $arElementIDList = $this->arElementIDList;
        $arResult = $obCollection->all();
        foreach ($arResult as $iKey => $obItem) {
            self::assertEquals(array_shift($arElementIDList), $obItem->id, $sMessage);
        }
    }

    /**
     * Test take method in item collection class
     */
    public function testTakeMethod()
    {
        $sMessage = 'Error in "take" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);

        $arElementIDList = $this->arElementIDList;
        $arResult = $obCollection->take(2);

        self::assertEquals(2, count($arResult), $sMessage);
        foreach ($arResult as $iKey => $obItem) {
            self::assertEquals(array_shift($arElementIDList), $obItem->id, $sMessage);
        }

        $arElementIDList = $this->arElementIDList;
        $arResult = $obCollection->skip(1)->take(2);

        array_shift($arElementIDList);
        self::assertEquals(2, count($arResult), $sMessage);
        foreach ($arResult as $iKey => $obItem) {
            self::assertEquals(array_shift($arElementIDList), $obItem->id, $sMessage);
        }
    }

    /**
     * Test first method in item collection class
     */
    public function testFirstMethod()
    {
        $sMessage = 'Error in "first" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);

        $obItem = $obCollection->first();
        self::assertEquals($this->arElementIDList[0], $obItem->id, $sMessage);
    }

    /**
     * Test last method in item collection class
     */
    public function testLastMethod()
    {
        $sMessage = 'Error in "last" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);

        $obItem = $obCollection->last();
        self::assertEquals($this->arElementIDList[count($this->arElementIDList) -1], $obItem->id, $sMessage);
    }

    /**
     * Test shift method in item collection class
     */
    public function testShiftMethod()
    {
        $sMessage = 'Error in "shift" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);

        $obItem = $obCollection->shift();
        self::assertEquals(array_shift($this->arElementIDList), $obItem->id, $sMessage);
        self::assertEquals(count($this->arElementIDList), $obCollection->count(), $sMessage);
    }

    /**
     * Test pop method in item collection class
     */
    public function testPopMethod()
    {
        $sMessage = 'Error in "pop" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);

        $obItem = $obCollection->pop();
        self::assertEquals(array_pop($this->arElementIDList), $obItem->id, $sMessage);
        self::assertEquals(count($this->arElementIDList), $obCollection->count(), $sMessage);
    }

    /**
     * Test save/saved method in item collection class
     */
    public function testSaveMethod()
    {
        $sMessage = 'Error in "save" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);
        $obCollection->save('test');

        $obCollection = TestCollection::make()->saved('test');
        self::assertEquals($this->arElementIDList, $obCollection->getIDList(), $sMessage);
    }

    /**
     * Test "has" method in item collection class
     */
    public function testHasMethod()
    {
        $sMessage = 'Error in "has" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);

        $iElementID = array_shift($this->arElementIDList);
        self::assertEquals(true, $obCollection->has($iElementID), $sMessage);
    }
}