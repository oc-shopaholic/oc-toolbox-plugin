<?php namespace Lovata\Toolbox\Tests\Unit;

include_once __DIR__.'/../../../toolbox/vendor/autoload.php';
include_once __DIR__.'/../../../../../tests/PluginTestCase.php';

use PluginTestCase;
use Lovata\Toolbox\Classes\Item\TestItem;
use Lovata\Toolbox\Classes\Collection\TestCollection;

/**
 * Class CollectionTest
 * @package Lovata\Toolbox\Tests\Unit
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class CollectionTest extends PluginTestCase
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

        $sMessage = 'Error in "make" collection method';
        self::assertEquals(true, $obCollection->isNotEmpty(), $sMessage);
        self::assertEquals(false, $obCollection->isEmpty(), $sMessage);
    }

    /**
     * Test set method in item collection class
     */
    public function testSetMethod()
    {
        $sMessage = 'Error in "set" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);
        self::assertEquals($this->arElementIDList, $obCollection->getIDList(), $sMessage);

        $obCollection->set($this->arIntersectIDList);
        self::assertEquals($this->arIntersectIDList, $obCollection->getIDList(), $sMessage);
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
        self::assertEquals(false, $obCollection->has(null), $sMessage);
    }

    /**
     * Test find method in item collection class
     */
    public function testFindMethod()
    {
        $sMessage = 'Error in "find" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);

        $obItem = $obCollection->find($this->arElementIDList[0]);
        self::assertEquals($this->arElementIDList[0], $obItem->id, $sMessage);
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

        $obCollection = TestCollection::make();
        self::assertEquals(0, $obCollection->count(), $sMessage);
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
        $arResult = array_values($arResult);

        self::assertEquals($arResult, $obCollection->getIDList(), $sMessage);

        //Test intersect with empty array
        $obCollection = TestCollection::make($this->arElementIDList);
        $obCollection->intersect(null);

        self::assertEquals([], $obCollection->getIDList(), $sMessage);

        //Test intersect with clear collection
        $obCollection = TestCollection::make();
        $obCollection->intersect($this->arIntersectIDList);

        self::assertEquals($this->arIntersectIDList, $obCollection->getIDList(), $sMessage);

        $obCollection = TestCollection::make()->intersect(null);
        $obCollection->intersect($this->arIntersectIDList);

        self::assertEquals([], $obCollection->getIDList(), $sMessage);
    }

    /**
     * Test applySorting method in item collection class
     */
    public function testApplySortingMethod()
    {
        $arSortedList = [5,3,4, 12, 20];

        $sMessage = 'Error in "applySorting" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);
        $obCollection->applySorting($arSortedList);

        $arResult = array_intersect($arSortedList, $this->arElementIDList);

        self::assertEquals($arResult, $obCollection->getIDList(), $sMessage);

        //Test intersect with empty array
        $obCollection = TestCollection::make($this->arElementIDList);
        $obCollection->applySorting(null);

        self::assertEquals([], $obCollection->getIDList(), $sMessage);

        //Test intersect with clear collection
        $obCollection = TestCollection::make();
        $obCollection->applySorting($this->arIntersectIDList);

        self::assertEquals($this->arIntersectIDList, $obCollection->getIDList(), $sMessage);

        $obCollection = TestCollection::make()->intersect(null);
        $obCollection->applySorting($arSortedList);

        self::assertEquals([], $obCollection->getIDList(), $sMessage);
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
        $arResult = array_values($arResult);

        self::assertEquals($arResult, $obCollection->getIDList(), $sMessage);

        //test merge with empty array
        $obCollection = TestCollection::make($this->arElementIDList);
        $obCollection->merge(null);

        self::assertEquals($this->arElementIDList, $obCollection->getIDList(), $sMessage);

        //test merge with empty collection
        $obCollection = TestCollection::make();
        $obCollection->merge($this->arIntersectIDList);

        self::assertEquals($this->arIntersectIDList, $obCollection->getIDList(), $sMessage);
    }

    /**
     * Test diff method in item collection class
     */
    public function testDiffMethod()
    {
        $sMessage = 'Error in "diff" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);
        $obCollection->diff($this->arIntersectIDList);

        $arResult = array_diff($this->arElementIDList, $this->arIntersectIDList);

        self::assertEquals($arResult, $obCollection->getIDList(), $sMessage);

        //Test method with empty array
        $obCollection = TestCollection::make($this->arElementIDList);
        $obCollection->diff(null);

        self::assertEquals($this->arElementIDList, $obCollection->getIDList(), $sMessage);

        //Test method empty diff result
        $obCollection = TestCollection::make($this->arElementIDList);
        $obCollection->diff($this->arElementIDList);

        self::assertEquals([], $obCollection->getIDList(), $sMessage);
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

        $obCollection = TestCollection::make();

        $arResult = $obCollection->all();
        self::assertEquals([], $arResult, $sMessage);
    }

    /**
     * Test take method in item collection class
     */
    public function testTakeMethod()
    {
        $sMessage = 'Error in "take" collection method';

        $obCollection = TestCollection::make([]);
        $arResult = $obCollection->take(2);
        self::assertEquals([], $arResult, $sMessage);
        
        $obCollection = TestCollection::make($this->arElementIDList);

        $arElementIDList = $this->arElementIDList;
        $arResult = $obCollection->take(null);

        self::assertEquals(count($arElementIDList), count($arResult), $sMessage);
        foreach ($arResult as $iKey => $obItem) {
            self::assertEquals(array_shift($arElementIDList), $obItem->id, $sMessage);
        }
        
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

        $arResult = $obCollection->skip(10)->take(2);
        self::assertEquals([], $arResult, $sMessage);
    }

    /**
     * Test exclude method in item collection class
     */
    public function testExcludeMethod()
    {
        $sMessage = 'Error in "exclude" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);

        $obItem = $obCollection->find(3);
        self::assertEquals(true, $obItem->isNotEmpty(), $sMessage);

        $obCollection->exclude(3);

        $obItem = $obCollection->find(3);
        self::assertEquals(true, $obItem->isEmpty(), $sMessage);

        //Exclude from empty collection
        $obCollection = TestCollection::make();
        $obCollection->exclude(3);

        self::assertEquals(true, $obItem->isEmpty(), $sMessage);

        //Exclude missing element
        $obCollection = TestCollection::make($this->arElementIDList);
        $obCollection->exclude(15);

        self::assertEquals(count($this->arElementIDList), $obCollection->count(), $sMessage);
    }

    /**
     * Test random method in item collection class
     */
    public function testRandomMethod()
    {
        $sMessage = 'Error in "random" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);

        $arResult = $obCollection->random(1);
        $obItem = array_shift($arResult);
        self::assertEquals(true, $obCollection->has($obItem->id), $sMessage);

        $arResult = $obCollection->random(-1);
        $obItem = array_shift($arResult);
        self::assertEquals(true, $obCollection->has($obItem->id), $sMessage);
        
        $arResult = $obCollection->random($obCollection->count() + 1);
        self::assertEquals($obCollection->count(), count($arResult), $sMessage);
        
        $obCollection = TestCollection::make();

        $arResult = $obCollection->random(1);
        self::assertEquals([], $arResult, $sMessage);
    }

    /**
     * Test page method in item collection class
     */
    public function testPageMethod()
    {
        $sMessage = 'Error in "page" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);

        $arElementIDList = $this->arElementIDList;
        $arResult = $obCollection->page(2, 1);

        self::assertEquals(1, count($arResult), $sMessage);

        array_shift($arElementIDList);
        foreach ($arResult as $iKey => $obItem) {
            self::assertEquals(array_shift($arElementIDList), $obItem->id, $sMessage);
        }

        $arResult = $obCollection->page(-1, 1);

        self::assertEquals(1, count($arResult), $sMessage);

        $arElementIDList = $this->arElementIDList;
        foreach ($arResult as $iKey => $obItem) {
            self::assertEquals(array_shift($arElementIDList), $obItem->id, $sMessage);
        }

        $arResult = $obCollection->page(1, -1);
        self::assertEquals($obCollection->count(), count($arResult), $sMessage);
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
        self::assertEquals(count($this->arElementIDList), $obCollection->count(), $sMessage);

        $obCollection = TestCollection::make();

        $obItem = $obCollection->first();
        self::assertEquals(true, $obItem->isEmpty(), $sMessage);
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
        self::assertEquals(count($this->arElementIDList), $obCollection->count(), $sMessage);

        $obCollection = TestCollection::make();

        $obItem = $obCollection->last();
        self::assertEquals(true, $obItem->isEmpty(), $sMessage);
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

        $obCollection = TestCollection::make();

        $obItem = $obCollection->shift();
        self::assertEquals(true, $obItem->isEmpty(), $sMessage);
    }

    /**
     * Test unshift method in item collection class
     */
    public function testUnshiftMethod()
    {
        $sMessage = 'Error in "unshift" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);

        $obCollection->unshift(null);
        self::assertEquals(count($this->arElementIDList), $obCollection->count(), $sMessage);

        $obCollection->unshift(10);

        $obItem = $obCollection->first();
        self::assertEquals(10, $obItem->id, $sMessage);

        $obCollection = TestCollection::make();

        $obCollection->unshift(10);
        $obItem = $obCollection->first();
        self::assertEquals(10, $obItem->id, $sMessage);
        self::assertEquals(1, $obCollection->count(), $sMessage);
    }


    /**
     * Test push method in item collection class
     */
    public function testPushMethod()
    {
        $sMessage = 'Error in "push" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);

        $obCollection->push(null);
        self::assertEquals(count($this->arElementIDList), $obCollection->count(), $sMessage);

        $obCollection->push(10);

        $obItem = $obCollection->last();
        self::assertEquals(10, $obItem->id, $sMessage);

        $obCollection = TestCollection::make();

        $obCollection->push(10);
        $obItem = $obCollection->last();
        self::assertEquals(10, $obItem->id, $sMessage);
        self::assertEquals(1, $obCollection->count(), $sMessage);
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

        $obCollection = TestCollection::make();

        $obItem = $obCollection->pop();
        self::assertEquals(true, $obItem->isEmpty(), $sMessage);
    }

    /**
     * Test pluck method in item collection class
     */
    public function testPluckMethod()
    {
        $sMessage = 'Error in "pluck" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);

        $arResult = $obCollection->pluck('id');
        self::assertEquals($this->arElementIDList, $arResult, $sMessage);

        $obCollection = TestCollection::make();

        $arResult = $obCollection->pluck('id');
        self::assertEquals(null, $arResult, $sMessage);
    }

    /**
     * Test implode method in item collection class
     */
    public function testImplodeMethod()
    {
        $sMessage = 'Error in "implode" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);

        $sResult = $obCollection->implode('id');
        self::assertEquals(implode(', ', $this->arElementIDList), $sResult, $sMessage);

        $sResult = $obCollection->implode('id', '-');
        self::assertEquals(implode('-', $this->arElementIDList), $sResult, $sMessage);

        $obCollection = TestCollection::make();

        $sResult = $obCollection->implode('id');
        self::assertEquals(null, $sResult, $sMessage);
    }

    /**
     * Test getNearestNext method in item collection class
     */
    public function testGetNearestNextMethod()
    {
        $sMessage = 'Error in "getNearestNext" collection method';

        $obCollection = TestCollection::make();

        //Test method with empty collection
        $obResult = $obCollection->getNearestNext(1);

        self::assertInstanceOf(TestCollection::class, $obResult, $sMessage);
        self::assertEquals(true, $obResult->isEmpty(), $sMessage);

        //Get not empty collection
        $obCollection = TestCollection::make($this->arElementIDList);

        //Test method with empty data
        $obResult = $obCollection->getNearestNext(null);

        self::assertInstanceOf(TestCollection::class, $obResult, $sMessage);
        self::assertEquals(true, $obResult->isEmpty(), $sMessage);

        $obResult = $obCollection->getNearestNext(1, 0);

        self::assertInstanceOf(TestCollection::class, $obResult, $sMessage);
        self::assertEquals(true, $obResult->isEmpty(), $sMessage);

        $obResult = $obCollection->getNearestNext(1, -1);

        self::assertInstanceOf(TestCollection::class, $obResult, $sMessage);
        self::assertEquals(true, $obResult->isEmpty(), $sMessage);

        $obResult = $obCollection->getNearestNext(100);

        self::assertInstanceOf(TestCollection::class, $obResult, $sMessage);
        self::assertEquals(true, $obResult->isEmpty(), $sMessage);

        //Get nearest elements #1
        $obResult = $obCollection->getNearestNext(1);

        self::assertInstanceOf(TestCollection::class, $obResult, $sMessage);
        self::assertEquals(1, $obResult->count(), $sMessage);

        $obItem = $obResult->first();
        self::assertInstanceOf(TestItem::class, $obItem, $sMessage);
        self::assertEquals(2, $obItem->id, $sMessage);

        //Get nearest elements #2
        $obResult = $obCollection->getNearestNext(5);

        self::assertInstanceOf(TestCollection::class, $obResult, $sMessage);
        self::assertEquals(true, $obResult->isEmpty(), $sMessage);

        //Get nearest elements #3
        $obResult = $obCollection->getNearestNext(4, 2);

        self::assertInstanceOf(TestCollection::class, $obResult, $sMessage);
        self::assertEquals(1, $obResult->count(), $sMessage);

        $obItem = $obResult->first();
        self::assertInstanceOf(TestItem::class, $obItem, $sMessage);
        self::assertEquals(5, $obItem->id, $sMessage);

        //Get nearest elements #4
        $obResult = $obCollection->getNearestNext(4, 2, true);

        self::assertInstanceOf(TestCollection::class, $obResult, $sMessage);
        self::assertEquals(2, $obResult->count(), $sMessage);

        $obItem = $obResult->last();
        self::assertInstanceOf(TestItem::class, $obItem, $sMessage);
        self::assertEquals(1, $obItem->id, $sMessage);

        //Get nearest elements #10
        $obResult = $obCollection->getNearestNext(10, 2, true);

        self::assertInstanceOf(TestCollection::class, $obResult, $sMessage);
        self::assertEquals(0, $obResult->count(), $sMessage);
    }

    /**
     * Test getNearestPrev method in item collection class
     */
    public function testGetNearestPrevMethod()
    {
        $sMessage = 'Error in "getNearestPrev" collection method';

        $obCollection = TestCollection::make();

        //Test method with empty collection
        $obResult = $obCollection->getNearestPrev(1);

        self::assertInstanceOf(TestCollection::class, $obResult, $sMessage);
        self::assertEquals(true, $obResult->isEmpty(), $sMessage);

        //Get not empty collection
        $obCollection = TestCollection::make($this->arElementIDList);

        //Test method with empty data
        $obResult = $obCollection->getNearestPrev(null);

        self::assertInstanceOf(TestCollection::class, $obResult, $sMessage);
        self::assertEquals(true, $obResult->isEmpty(), $sMessage);

        $obResult = $obCollection->getNearestPrev(1, 0);

        self::assertInstanceOf(TestCollection::class, $obResult, $sMessage);
        self::assertEquals(true, $obResult->isEmpty(), $sMessage);

        $obResult = $obCollection->getNearestPrev(1, -1);

        self::assertInstanceOf(TestCollection::class, $obResult, $sMessage);
        self::assertEquals(true, $obResult->isEmpty(), $sMessage);

        $obResult = $obCollection->getNearestPrev(100);

        self::assertInstanceOf(TestCollection::class, $obResult, $sMessage);
        self::assertEquals(true, $obResult->isEmpty(), $sMessage);

        //Get nearest elements #1
        $obResult = $obCollection->getNearestPrev(5);

        self::assertInstanceOf(TestCollection::class, $obResult, $sMessage);
        self::assertEquals(1, $obResult->count(), $sMessage);

        $obItem = $obResult->first();
        self::assertInstanceOf(TestItem::class, $obItem, $sMessage);
        self::assertEquals(4, $obItem->id, $sMessage);

        //Get nearest elements #2
        $obResult = $obCollection->getNearestPrev(1);

        self::assertInstanceOf(TestCollection::class, $obResult, $sMessage);
        self::assertEquals(true, $obResult->isEmpty(), $sMessage);

        //Get nearest elements #3
        $obResult = $obCollection->getNearestPrev(2, 2);

        self::assertInstanceOf(TestCollection::class, $obResult, $sMessage);
        self::assertEquals(1, $obResult->count(), $sMessage);

        $obItem = $obResult->first();
        self::assertInstanceOf(TestItem::class, $obItem, $sMessage);
        self::assertEquals(1, $obItem->id, $sMessage);

        //Get nearest elements #4
        $obResult = $obCollection->getNearestPrev(2, 2, true);

        self::assertInstanceOf(TestCollection::class, $obResult, $sMessage);
        self::assertEquals(2, $obResult->count(), $sMessage);

        $obItem = $obResult->last();
        self::assertInstanceOf(TestItem::class, $obItem, $sMessage);
        self::assertEquals(5, $obItem->id, $sMessage);

        //Get nearest elements #10
        $obResult = $obCollection->getNearestPrev(10, 2, true);

        self::assertInstanceOf(TestCollection::class, $obResult, $sMessage);
        self::assertEquals(0, $obResult->count(), $sMessage);
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

        $obCollection->save(null);

        $obCollection = TestCollection::make()->saved(null);
        self::assertEquals(null, $obCollection, $sMessage);
    }

    /**
     * Test debug method in item collection class
     */
    public function testDebugMethod()
    {
        $sMessage = 'Error in "debug" collection method';
        $obCollection = TestCollection::make($this->arElementIDList);
        
            self::assertEquals($obCollection, $obCollection->debug(), $sMessage);
    }
    
    /**
     * Test iterator interface in item collection class
     */
    public function testIteratorInterface()
    {
        $sMessage = 'Error in iteration collection';
        $obCollection = TestCollection::make($this->arElementIDList);

        foreach ($obCollection as $iKey => $obItem) {
            self::assertEquals(array_shift($this->arElementIDList), $obItem->id, $sMessage);
        }

        $obCollection = TestCollection::make([]);

        foreach ($obCollection as $iKey => $obItem) {
            self::assertEquals(array_shift($this->arElementIDList), $obItem->id, $sMessage);
        }
    }
}