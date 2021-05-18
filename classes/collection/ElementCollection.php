<?php namespace Lovata\Toolbox\Classes\Collection;

use ArrayIterator;
use October\Rain\Extension\Extendable;

/**
 * Class ElementCollection
 * @package Lovata\Toolbox\Classes\Collection
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @link    https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection
 */
abstract class ElementCollection extends Extendable implements \IteratorAggregate, \Countable
{
    const COUNT_PER_PAGE = 10;
    const ITEM_CLASS = \Lovata\Toolbox\Classes\Item\ElementItem::class;

    protected $iPosition = 0;

    /** @var array */
    protected $arElementIDList = null;

    /** @var int Skip element count, used in "take" method */
    protected $iSkip = 0;

    /**
     * Make new list store
     * @param array $arElementIDList - element ID list
     * @return $this
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testMakeMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#makearelementidlist--
     */
    public static function make($arElementIDList = [])
    {
        /** @var $this $obCollection */
        $obCollection = app()->make(static::class);

        if (!empty($arElementIDList) && is_array($arElementIDList)) {
            $obCollection->arElementIDList = $arElementIDList;
        }

        return $obCollection->returnThis();
    }

    /**
     * Return this collection
     * @return $this
     */
    public function returnThis()
    {
        return $this;
    }

    /**
     * Check list is clear
     * @return bool
     */
    public function isClear(): bool
    {
        return $this->arElementIDList === null;
    }

    /**
     * Check list is empty
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#isempty
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->arElementIDList);
    }

    /**
     * Check list is not empty
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#isnotempty
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * Get element ID list
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#getidlist
     * @return array
     */
    public function getIDList(): array
    {
        return array_values((array) $this->arElementIDList);
    }

    /**
     * Set new
     * @param array $arElementIDList
     * @return $this
     */
    public function set($arElementIDList)
    {
        if (!is_array($arElementIDList)) {
            return $this->returnThis();
        }

        $this->arElementIDList = $arElementIDList;

        return $this->returnThis();
    }

    /**
     * Checking, has collection ID
     * @param int $iElementID
     * @return bool
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testHasMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#hasielementid
     */
    public function has($iElementID)
    {
        if (empty($iElementID) || $this->isEmpty()) {
            return false;
        }

        return in_array($iElementID, (array) $this->arElementIDList);
    }

    /**
     * Get element item with ID
     * @param int $iElementID
     * @return \Lovata\Toolbox\Classes\Item\ElementItem
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testFindMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#findielementid
     */
    public function find($iElementID)
    {
        if (!$this->has($iElementID)) {
            return $this->makeItem(null);
        }

        return $this->makeItem($iElementID);
    }

    /**
     * Set clear array to element list
     * @return $this
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#clear
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testClearMethod()
     */
    public function clear()
    {
        $this->arElementIDList = [];

        return $this->returnThis();
    }

    /**
     * Get element count
     * @return int
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#count
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testCountMethod()
     */
    public function count()
    {
        if ($this->isEmpty()) {
            return 0;
        }

        return count((array) $this->arElementIDList);
    }

    /**
     * Apply array_intersect for element array list
     * @param array $arElementIDList
     * @return $this
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testIntersectMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#intersectarelementidlist
     */
    public function intersect($arElementIDList)
    {
        if (empty($arElementIDList)) {
            return $this->clear();
        }

        if ($this->isClear()) {
            $this->arElementIDList = $arElementIDList;

            return $this->returnThis();
        }

        $this->arElementIDList = array_combine($this->arElementIDList, $this->arElementIDList);
        $arElementIDList = array_combine($arElementIDList, $arElementIDList);

        $this->arElementIDList = array_intersect_key($this->arElementIDList, $arElementIDList);

        return $this->returnThis();
    }

    /**
     * Apply sorting for elements of collection
     * @param array $arResultIDList
     * @return $this
     */
    public function applySorting($arResultIDList)
    {
        if (empty($arResultIDList)) {
            return $this->clear();
        }

        if (!$this->isClear() && $this->isEmpty()) {
            return $this->returnThis();
        }

        if ($this->isClear()) {
            $this->arElementIDList = $arResultIDList;

            return $this->returnThis();
        }

        $this->arElementIDList = array_combine($this->arElementIDList, $this->arElementIDList);
        $arResultIDList = array_combine($arResultIDList, $arResultIDList);

        $this->arElementIDList = array_intersect_key($arResultIDList, $this->arElementIDList);

        return $this->returnThis();
    }

    /**
     * Apply array_merge for element array list
     * @param array $arElementIDList
     * @return $this
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testMergeMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#mergearelementidlist
     */
    public function merge($arElementIDList)
    {
        if (empty($arElementIDList)) {
            return $this->returnThis();
        }

        if ($this->isClear()) {
            $this->arElementIDList = $arElementIDList;

            return $this->returnThis();
        }

        $this->arElementIDList = array_merge($this->arElementIDList, $arElementIDList);
        $this->arElementIDList = array_unique($this->arElementIDList);

        return $this->returnThis();
    }

    /**
     * Apply array_diff for element array list
     * @param array $arExcludeIDList
     * @return $this
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testDiffMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#diffarelementidlist
     */
    public function diff($arExcludeIDList = [])
    {
        if (empty($arExcludeIDList) || $this->isEmpty()) {
            return $this->returnThis();
        }

        $this->arElementIDList = array_diff($this->arElementIDList, $arExcludeIDList);

        return $this->returnThis();
    }

    /**
     * Get element item list
     * @return array|\Lovata\Toolbox\Classes\Item\ElementItem[]
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#all
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testAllMethod()
     */
    public function all()
    {
        if ($this->isEmpty()) {
            return [];
        }

        $arResult = $this->prepareItemList($this->arElementIDList);

        return $arResult;
    }

    /**
     * Set skip element count
     * @param int $iCount
     * @return $this
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testTakeMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#skipicount
     * Used in "take" method
     */
    public function skip($iCount)
    {
        $this->iSkip = (int) trim($iCount);

        return $this->returnThis();
    }

    /**
     * Take array with element items
     * @param int $iCount
     * @return array|\Lovata\Toolbox\Classes\Item\ElementItem[]
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testTakeMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#takeicount--0
     */
    public function take($iCount = 0)
    {
        $iCount = (int) trim($iCount);
        if ($this->isEmpty()) {
            return [];
        }

        if (empty($iCount)) {
            $iCount = null;
        }

        $arResultIDList = array_slice($this->arElementIDList, $this->iSkip, $iCount);
        if (empty($arResultIDList)) {
            return [];
        }

        $arResult = $this->prepareItemList($arResultIDList);

        return $arResult;
    }

    /**
     * Exclude element id from collection
     * @param int $iElementID
     * @return $this
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testExcludeMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#excludeielementid
     */
    public function exclude($iElementID = null)
    {
        if (empty($iElementID) || $this->isEmpty()) {
            return $this->returnThis();
        }

        $iElementIDKey = array_search($iElementID, $this->arElementIDList);
        if ($iElementIDKey === false) {
            return $this->returnThis();
        }

        unset($this->arElementIDList[$iElementIDKey]);

        return $this->returnThis();
    }

    /**
     * Take array with random element items
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#randomicount
     * @param int $iCount
     * @return array|\Lovata\Toolbox\Classes\Item\ElementItem[]
     */
    public function random($iCount = 1)
    {
        if ($this->isEmpty()) {
            return [];
        }

        $iCount = (int) trim($iCount);
        if ($iCount < 1) {
            $iCount = 1;
        }

        if (count($this->arElementIDList) < $iCount) {
            $iCount = count($this->arElementIDList);
        }

        $obThis = $this->copy();

        $arResultIDList = $obThis->getIDList();
        $arKeyList = array_rand($arResultIDList, $iCount);
        if (!is_array($arKeyList)) {
            $arKeyList = [$arKeyList];
        }

        $arKeyList = array_combine($arKeyList, $arKeyList);
        $arResultIDList = array_intersect_key($arResultIDList, $arKeyList);

        return $obThis->intersect($arResultIDList)->all();
    }

    /**
     * Apply pagination for item collection
     * @param int $iPage
     * @param int $iElementOnPage
     *
     * @return array|\Lovata\Toolbox\Classes\Item\ElementItem[]|null
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#pageipage-ielementonpage--10
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testPageMethod()
     */
    public function page($iPage, $iElementOnPage = 10)
    {
        $iPage = (int) trim($iPage);

        //Check page value
        if ($iPage < 1) {
            $iPage = 1;
        }

        if ($iElementOnPage < 1) {
            $iElementOnPage = self::COUNT_PER_PAGE;
        }

        return $this->skip(($iPage - 1) * $iElementOnPage)->take($iElementOnPage);
    }

    /**
     * Get first element item
     * @return \Lovata\Toolbox\Classes\Item\ElementItem|null
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#first
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testFirstMethod()
     */
    public function first()
    {
        if ($this->isEmpty()) {
            return $this->makeItem(null);
        }

        $arResultIDList = $this->arElementIDList;

        $iElementID = array_shift($arResultIDList);

        return $this->makeItem($iElementID);
    }

    /**
     * Get last element item
     * @return \Lovata\Toolbox\Classes\Item\ElementItem|null
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#last
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testLastMethod()
     */
    public function last()
    {
        if ($this->isEmpty()) {
            return $this->makeItem(null);
        }

        $arResultIDList = $this->arElementIDList;

        $iElementID = array_pop($arResultIDList);

        return $this->makeItem($iElementID);
    }

    /**
     * Apply array_shift to element ID list and get first element item
     * @return \Lovata\Toolbox\Classes\Item\ElementItem|null
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#shift
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testShiftMethod()
     */
    public function shift()
    {
        if (empty($this->arElementIDList)) {
            return $this->makeItem(null);
        }

        $iElementID = array_shift($this->arElementIDList);

        return $this->makeItem($iElementID);
    }

    /**
     * Apply array_unshift to element ID
     * @param int $iElementID
     * @return $this
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#unshiftielementid
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testUnshiftMethod()
     */
    public function unshift($iElementID)
    {
        if (empty($iElementID)) {
            return $this->returnThis();
        }

        if ($this->isEmpty()) {
            $this->arElementIDList = [$iElementID];

            return $this->returnThis();
        }

        array_unshift($this->arElementIDList, $iElementID);

        return $this->returnThis();
    }

    /**
     * Apply array_pop to element ID list and get first element item
     * @return \Lovata\Toolbox\Classes\Item\ElementItem|null
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#pop
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testPopMethod()
     */
    public function pop()
    {
        if ($this->isEmpty()) {
            return $this->makeItem(null);
        }

        $iElementID = array_pop($this->arElementIDList);

        return $this->makeItem($iElementID);
    }

    /**
     * Push element ID to end of list
     * @param int $iElementID
     * @return $this
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#pushielementid
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testUnshiftMethod()
     */
    public function push($iElementID)
    {
        if (empty($iElementID)) {
            return $this->returnThis();
        }

        if ($this->isEmpty()) {
            $this->arElementIDList = [$iElementID];

            return $this->returnThis();
        }

        $this->arElementIDList[] = $iElementID;

        return $this->returnThis();
    }

    /**
     * Get array with item field value
     * @param string $sFieldName
     * @return array|null
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testPluckMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#plucksfieldname
     */
    public function pluck($sFieldName)
    {
        if (empty($sFieldName) || $this->isEmpty()) {
            return null;
        }

        //Get all items
        $arItemList = $this->all();

        $arResult = [];
        foreach ($arItemList as $obItem) {
            if ($obItem->isEmpty()) {
                continue;
            }

            $arResult[] = $obItem->$sFieldName;
        }

        return $arResult;
    }

    /**
     * Get implode string with item field value
     * @param string $sFieldName
     * @param string $sDelimiter
     * @return null
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#implodesfieldname-sdelimiter---
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testImplodeMethod()
     */
    public function implode($sFieldName, $sDelimiter = ', ')
    {
        if (empty($sFieldName) || $this->isEmpty()) {
            return null;
        }

        //Get field value array
        $arFieldValue = $this->pluck($sFieldName);
        if (empty($arFieldValue)) {
            return null;
        }

        $sResult = implode($sDelimiter, $arFieldValue);

        return $sResult;
    }

    /**
     * Get new collection with next nearest elements
     * @param int  $iElementID
     * @param int  $iCount
     * @param bool $bCyclic
     * @return $this
     */
    public function getNearestNext($iElementID, $iCount = 1, $bCyclic = false)
    {
        $obList = self::make();
        if (empty($iElementID) || empty($iCount) || $iCount < 1) {
            return $obList->returnThis();
        }

        //Check current collection
        if ($this->isEmpty() || !$this->has($iElementID)) {
            return $obList->returnThis();
        }

        $this->arElementIDList = array_values($this->arElementIDList);

        //Search element position
        $iElementPosition = array_search($iElementID, $this->arElementIDList);

        //Get next elements
        $arResultIDList = array_slice($this->arElementIDList, $iElementPosition + 1);
        if ($bCyclic && $iElementPosition >= 1) {
            //Get elements from start of array
            $arAdditionElementIDList = array_slice($this->arElementIDList, 0, $iElementPosition);
            $arResultIDList = array_merge($arResultIDList, $arAdditionElementIDList);
        }

        //Get result element ID list
        $arResultIDList = array_slice($arResultIDList, 0, $iCount);
        $obList->intersect($arResultIDList);

        return $obList->returnThis();
    }

    /**
     * Get new collection with prev nearest elements
     * @param int  $iElementID
     * @param int  $iCount
     * @param bool $bCyclic
     * @return $this
     */
    public function getNearestPrev($iElementID, $iCount = 1, $bCyclic = false)
    {
        $obList = self::make();
        if (empty($iElementID) || empty($iCount) || $iCount < 1) {
            return $obList->returnThis();
        }

        //Check current collection
        if ($this->isEmpty() || !$this->has($iElementID)) {
            return $obList->returnThis();
        }

        $this->arElementIDList = array_values($this->arElementIDList);

        //Search element position
        $iElementPosition = array_search($iElementID, $this->arElementIDList);

        //Get prev elements
        $arResultIDList = (array) array_slice($this->arElementIDList, 0, $iElementPosition);
        $arResultIDList = array_reverse($arResultIDList);

        if ($bCyclic && $iElementPosition < count($this->arElementIDList)) {
            //Get elements from end of array
            $arAdditionElementIDList = (array) array_slice($this->arElementIDList, $iElementPosition);
            $arAdditionElementIDList = array_reverse($arAdditionElementIDList);

            $arResultIDList = array_merge($arResultIDList, $arAdditionElementIDList);
        }

        //Get result element ID list
        $arResultIDList = array_slice($arResultIDList, 0, $iCount);
        $obList->intersect($arResultIDList);

        return $obList->returnThis();
    }

    /**
     * Save item collection in store
     * @param string $sKey
     * @return $this
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testSaveMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#saveskeysavedskey
     */
    public function save($sKey)
    {
        if (empty($sKey)) {
            return $this;
        }

        $sKey = static::class.'@'.$sKey;
        CollectionStore::instance()->save($sKey, $this);

        return $this->returnThis();
    }

    /**
     * Get saved item collection
     * @param string $sKey
     * @return $this
     * @see  \Lovata\Toolbox\Tests\Unit\CollectionTest::testSaveMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#saveskeysavedskey
     */
    public function saved($sKey)
    {
        if (empty($sKey)) {
            return null;
        }

        $sKey = static::class.'@'.$sKey;

        $obCollection = CollectionStore::instance()->saved($sKey);
        if (empty($obCollection)) {
            return null;
        }

        return $obCollection;
    }

    /**
     * Clone collection object
     * @return $this
     */
    public function copy()
    {
        return static::make()->intersect($this->getIDList());
    }

    /**
     * Helper method for collection debug
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#debug
     * @return $this
     */
    public function debug()
    {
        return $this->returnThis();
    }

    /**
     * Get an iterator for the items.
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->all());
    }

    /**
     * Make element item
     * @param int    $iElementID
     * @param \Model $obElement
     * @return \Lovata\Toolbox\Classes\Item\ElementItem
     */
    protected function makeItem($iElementID, $obElement = null)
    {
        $sItemClass = static::ITEM_CLASS;

        return $sItemClass::make($iElementID, $obElement);
    }

    /**
     * Make element item from cache only
     * @param int $iElementID
     * @return \Lovata\Toolbox\Classes\Item\ElementItem
     */
    protected function makeItemOnlyCache($iElementID)
    {
        $sItemClass = static::ITEM_CLASS;

        return $sItemClass::makeOnlyCache($iElementID);
    }

    /**
     * Prepare item list
     * @param array $arElementIDList
     * @return array
     */
    protected function prepareItemList($arElementIDList): array
    {
        $arResult = [];
        if (empty($arElementIDList)) {
            return $arResult;
        }

        $arEmptyIDList = [];

        foreach ($arElementIDList as $iElementID) {
            /** @var \Lovata\Toolbox\Classes\Item\ElementItem $obElementItem */
            $obElementItem = static::makeItemOnlyCache($iElementID);
            if ($obElementItem->isEmpty()) {
                $arEmptyIDList[] = $iElementID;
                $arResult[$iElementID] = null;
            } else {
                $arResult[$iElementID] = $obElementItem;
            }
        }

        $arResult = $this->getElementListFromDB($arEmptyIDList, $arResult);
        $arResult = array_filter($arResult);

        return $arResult;
    }

    /**
     * Get element list from DB
     * @param array $arElementIDList
     * @param array $arResult
     * @return array
     */
    protected function getElementListFromDB($arElementIDList, $arResult)
    {
        if (empty($arElementIDList)) {
            return $arResult;
        }

        $arChunkIDList = array_chunk($arElementIDList, 10000);
        $sItemClass = static::ITEM_CLASS;
        $sModelClass = $sItemClass::MODEL_CLASS;

        foreach ($arChunkIDList as $arSingleChunk) {
            $obQuery = $sModelClass::whereIn($sItemClass::QUERY_FIELD, $arElementIDList);
            if (method_exists($sModelClass, 'trashed')) {
                $obQuery->withTrashed();
            }

            if (!empty($sItemClass::$arQueryWith)) {
                $obQuery->with($sItemClass::$arQueryWith);
            }

            $obElementList = $obQuery->get();

            foreach ($arSingleChunk as $iElementID) {
                $obElement = !empty($obElementList) ? $obElementList->find($iElementID) : null;
                $obElementItem = static::makeItem($iElementID, $obElement);
                if ($obElementItem->isEmpty()) {
                    continue;
                }

                $arResult[$iElementID] = $obElementItem;
            }
        }

        return $arResult;
    }
}
