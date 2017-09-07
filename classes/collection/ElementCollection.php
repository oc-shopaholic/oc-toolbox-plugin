<?php namespace Lovata\Toolbox\Classes\Collection;

use October\Rain\Extension\Extendable;

/**
 * Class ElementCollection
 * @package Lovata\Toolbox\Classes\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection
 */
abstract class ElementCollection extends Extendable  implements \Iterator
{
    const COUNT_PER_PAGE = 10;

    protected $iPosition = 0;
    
    /** @var array */
    protected $arElementIDList = null;

    /** @var int Skip element count, used in "take" method */
    protected $iSkip = 0;

    /**
     * ElementCollection constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Make new list store
     * @see \Lovata\Toolbox\Tests\Unit\CollectionTest::testMakeMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#makearelementidlist--
     * @param $arElementIDList
     * @return $this
     */
    public static function make($arElementIDList = [])
    {
        /** @var ElementCollection $obCollection */
        $obCollection = app()->make(static::class);

        if(!empty($arElementIDList) && is_array($arElementIDList)) {
            $obCollection->arElementIDList = $arElementIDList;
        }

        return $obCollection;
    }

    /**
     * Male new element item
     * @param int $iElementID
     * @param \Model $obElement
     * @return \Lovata\Toolbox\Classes\Item\ElementItem
     */
    protected abstract function makeItem($iElementID, $obElement = null);

    /**
     * Return this collection
     * @return $this
     */
    public function returnThis()
    {
        return $this;
    }
    
    /**
     * Check list is empty
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#isempty
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->arElementIDList);
    }
    
    /**
     * Check list is not empty
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#isnotempty
     * @return bool
     */
    public function isNotEmpty()
    {
        return !$this->isEmpty();
    }

    /**
     * Check list is clear
     * @return bool
     */
    protected function isClear()
    {
        return $this->arElementIDList === null;
    }

    /**
     * Get element ID list
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#getidlist
     * @return array
     */
    public function getIDList()
    {
        return $this->arElementIDList;
    }

    /**
     * Checking, has collection ID
     * @see \Lovata\Toolbox\Tests\Unit\CollectionTest::testHasMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#hasielementid
     * @param int $iElementID
     * @return bool
     */
    public function has($iElementID)
    {
        if(empty($iElementID) || $this->isEmpty()) {
            return false;
        }

        return in_array($iElementID, $this->arElementIDList);
    }

    /**
     * Get element item with ID
     * @see \Lovata\Toolbox\Tests\Unit\CollectionTest::testFindMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#findielementid
     * @param int $iElementID
     * @return \Lovata\Toolbox\Classes\Item\ElementItem
     */
    public function find($iElementID)
    {
        if(!$this->has($iElementID)) {
            return $this->makeItem(null);
        }
        
        return $this->makeItem($iElementID);
    }

    /**
     * Set clear array to element list
     * @see \Lovata\Toolbox\Tests\Unit\CollectionTest::testClearMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#clear
     * @return $this
     */
    public function clear()
    {
        $this->arElementIDList = [];
        return $this->returnThis();
    }

    /**
     * Get element count
     * @see \Lovata\Toolbox\Tests\Unit\CollectionTest::testCountMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#count
     * @return int
     */
    public function count()
    {
        if(empty($this->arElementIDList)) {
            return 0;
        }

        return count($this->arElementIDList);
    }

    /**
     * Apply array_intersect for element array list
     * @see \Lovata\Toolbox\Tests\Unit\CollectionTest::testIntersectMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#intersectarelementidlist
     * @param array $arElementIDList
     * @return $this
     */
    public function intersect($arElementIDList)
    {
        if(empty($arElementIDList)) {
            return $this->clear();
        }

        if($this->isClear()) {
            $this->arElementIDList = $arElementIDList;
            return $this->returnThis();
        }

        if(empty($this->arElementIDList)) {
            return $this->returnThis();
        }

        $this->arElementIDList = array_intersect($this->arElementIDList, $arElementIDList);
        return $this->returnThis();
    }

    /**
     * Apply array_merge for element array list
     * @see \Lovata\Toolbox\Tests\Unit\CollectionTest::testMergeMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#mergearelementidlist
     * @param array $arElementIDList
     * @return $this
     */
    public function merge($arElementIDList)
    {
        if(empty($arElementIDList)) {
            return $this->returnThis();
        }

        if($this->isClear()) {
            $this->arElementIDList = $arElementIDList;
            return $this->returnThis();
        }

        $this->arElementIDList = array_merge($this->arElementIDList, $arElementIDList);
        $this->arElementIDList = array_unique($this->arElementIDList);

        return $this->returnThis();
    }

    /**
     * Get element item list
     * @see \Lovata\Toolbox\Tests\Unit\CollectionTest::testAllMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#all
     * @return array|null|\Lovata\Toolbox\Classes\Item\ElementItem[]
     */
    public function all()
    {
        if(empty($this->arElementIDList)) {
            return null;
        }

        $arResult = [];
        foreach ($this->arElementIDList as $iElementID) {

            $obElementItem = $this->makeItem($iElementID, null);
            if($obElementItem->isEmpty()) {
                continue;
            }

            $arResult[$iElementID] = $obElementItem;
        }

        return $arResult;
    }

    /**
     * Set skip element count
     * @see \Lovata\Toolbox\Tests\Unit\CollectionTest::testTakeMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#skipicount
     * Used in "take" method
     * @param $iCount
     * @return $this
     */
    public function skip($iCount)
    {
        $this->iSkip = (int) trim($iCount);
        return $this->returnThis();
    }

    /**
     * Take array with element items
     * @see \Lovata\Toolbox\Tests\Unit\CollectionTest::testTakeMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#takeicount--0
     * @param int $iCount
     * @return array|null|\Lovata\Toolbox\Classes\Item\ElementItem[]
     */
    public function take($iCount = 0)
    {
        $iCount = (int) trim($iCount);
        if($this->isEmpty()) {
            return null;
        }
        
        if(empty($iCount)) {
            $iCount = null;
        }

        $arElementIDList = array_slice($this->arElementIDList, $this->iSkip, $iCount);
        if(empty($arElementIDList)) {
            return null;
        }

        $arResult = [];
        foreach ($arElementIDList as $iElementID) {

            $obElementItem = $this->makeItem($iElementID, null);
            if($obElementItem->isEmpty()) {
                continue;
            }

            $arResult[$iElementID] = $obElementItem;
        }

        return $arResult;
    }

    /**
     * Exclude element id from collection
     * @see \Lovata\Toolbox\Tests\Unit\CollectionTest::testExcludeMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#excludeielementid
     * @param int $iElementID
     * @return $this
     */
    public function exclude($iElementID = null)
    {
        if(empty($iElementID) || $this->isEmpty()) {
            return $this->returnThis();
        }

        $iElementIDKey = array_search($iElementID, $this->arElementIDList);
        if($iElementIDKey === false) {
            return $this->returnThis();
        }

        unset($this->arElementIDList[$iElementIDKey]);

        return $this->returnThis();
    }

    /**
     * Take array with random element items
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#randomicount
     * @param int $iCount
     * @return array|null|\Lovata\Toolbox\Classes\Item\ElementItem[]
     */
    public function random($iCount = 1)
    {
        if($this->isEmpty()) {
            return null;
        }

        $iCount = (int) trim($iCount);
        if($iCount < 1) {
            $iCount = 1;
        }

        if (count($this->arElementIDList) < $iCount) {
            $iCount = count($this->arElementIDList);
        }

        $arElementKeyList = array_rand($this->arElementIDList, $iCount);
        if(empty($arElementKeyList)) {
            return null;
        }

        $arResult = [];
        foreach ($arElementKeyList as $iElementKey) {
            $iElementID = $this->arElementIDList[$iElementKey];
            $obElementItem = $this->makeItem($iElementID);
            if($obElementItem->isEmpty()) {
                continue;
            }

            $arResult[$iElementID] = $obElementItem;
        }

        return $arResult;
    }

    /**
     * Apply pagination for item collection
     * @see \Lovata\Toolbox\Tests\Unit\CollectionTest::testPageMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#pageipage-ielementonpage--10
     * @param int $iPage
     * @param int $iElementOnPage
     *
     * @return array|\Lovata\Toolbox\Classes\Item\ElementItem[]|null
     */
    public function page($iPage, $iElementOnPage = 10)
    {
        $iPage = (int) trim($iPage);

        //Check page value
        if($iPage < 1) {
            $iPage = 1;
        }

        if($iElementOnPage < 1) {
            $iElementOnPage = self::COUNT_PER_PAGE;
        }

        return $this->skip(($iPage - 1) * $iElementOnPage)->take($iElementOnPage);
    }

    /**
     * Get first element item
     * @see \Lovata\Toolbox\Tests\Unit\CollectionTest::testFirstMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#first
     * @return \Lovata\Toolbox\Classes\Item\ElementItem|null
     */
    public function first()
    {
        if(empty($this->arElementIDList)) {
            return $this->makeItem(null);
        }

        $arElementIDList = array_values($this->arElementIDList);

        $iElementID = $arElementIDList[0];
        return $this->makeItem($iElementID, null);
    }

    /**
     * Get last element item
     * @see \Lovata\Toolbox\Tests\Unit\CollectionTest::testLastMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#last
     * @return \Lovata\Toolbox\Classes\Item\ElementItem|null
     */
    public function last()
    {
        if(empty($this->arElementIDList)) {
            return $this->makeItem(null);
        }

        $arElementIDList = array_values($this->arElementIDList);
        $iCount = count($arElementIDList);

        $iElementID = $arElementIDList[$iCount -1];
        return $this->makeItem($iElementID, null);
    }

    /**
     * Apply array_shift to element ID list and get first element item
     * @see \Lovata\Toolbox\Tests\Unit\CollectionTest::testShiftMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#shift
     * @return \Lovata\Toolbox\Classes\Item\ElementItem|null
     */
    public function shift()
    {
        if(empty($this->arElementIDList)) {
            return $this->makeItem(null);
        }

        $iElementID = array_shift($this->arElementIDList);
        return $this->makeItem($iElementID, null);
    }

    /**
     * Apply array_pop to element ID list and get first element item
     * @see \Lovata\Toolbox\Tests\Unit\CollectionTest::testPopMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#pop
     * @return \Lovata\Toolbox\Classes\Item\ElementItem|null
     */
    public function pop()
    {
        if(empty($this->arElementIDList)) {
            return $this->makeItem(null);
        }

        $iElementID = array_pop($this->arElementIDList);
        return $this->makeItem($iElementID, null);
    }

    /**
     * Save item collection in store
     * @see \Lovata\Toolbox\Tests\Unit\CollectionTest::testSaveMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#saveskeysavedskey
     * @param string $sKey
     *
     * @return $this
     */
    public function save($sKey)
    {
        if(empty($sKey)) {
            return $this;
        }

        $sKey = static::class.'@'.$sKey;
        CollectionStore::instance()->save($sKey, clone $this);

        return $this;
    }

    /**
     * Get saved item collection
     * @see \Lovata\Toolbox\Tests\Unit\CollectionTest::testSaveMethod()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#saveskeysavedskey
     * @param string $sKey
     *
     * @return $this
     */
    public function saved($sKey)
    {
        if(empty($sKey)) {
            return null;
        }

        $sKey = static::class.'@'.$sKey;

        $obCollection = CollectionStore::instance()->get($sKey);
        if(empty($obCollection)) {
             return null;
        }

        return clone $obCollection;
    }

    /**
     * Helper method for collection debug
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementCollection#debug
     */
    public function debug()
    {
        return $this->returnThis();
    }

    /**
     * Iterator method rewind
     */
    public function rewind()
    {
        $this->iPosition = 0;
    }

    /**
     * Iterator method current
     * @return \Lovata\Toolbox\Classes\Item\ElementItem
     */
    public function current()
    {
        if($this->isEmpty()) {
            return null;
        }
        
        $arElementIDList = array_values($this->arElementIDList);
        if(!isset($arElementIDList[$this->iPosition])) {
            return null;
        }
        
        return $this->makeItem($arElementIDList[$this->iPosition]);
    }

    /**
     * Iterator method key
     * @return string
     */
    public function key()
    {
        if($this->isEmpty()) {
            return null;
        }

        $arElementIDList = array_values($this->arElementIDList);
        if(!isset($arElementIDList[$this->iPosition])) {
            return null;
        }
        
        return $arElementIDList[$this->iPosition];
    }

    /**
     * Iterator method next
     */
    public function next()
    {
        $this->iPosition++;
    }

    /**
     * Iterator method valid
     * @return string
     */
    public function valid()
    {
        if($this->isEmpty()) {
            return null;
        }

        $arElementIDList = array_values($this->arElementIDList);
        return isset($arElementIDList[$this->iPosition]);
    }
}