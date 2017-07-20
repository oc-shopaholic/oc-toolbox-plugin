<?php namespace Lovata\Toolbox\Classes\Collection;

use October\Rain\Extension\Extendable;

/**
 * Class ElementCollection
 * @package Lovata\Toolbox\Classes\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class ElementCollection extends Extendable  implements \Iterator
{
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
     * @param $arElementIDList
     *
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
     *
     * @return \Lovata\Toolbox\Classes\Item\ElementItem
     */
    protected abstract function makeItem($iElementID, $obElement = null);

    /**
     * Return new clone collection
     * @return $this
     */
    protected function returnClone()
    {
        return $this;
    }
    
    /**
     * Check list is empty
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->arElementIDList);
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
     * @return array
     */
    public function getIDList()
    {
        return $this->arElementIDList;
    }

    /**
     * Checking, has collection ID
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
     * @param int $iElementID
     * @return \Lovata\Toolbox\Classes\Item\ElementItem
     */
    public function find($iElementID)
    {
        if(!$this->has($iElementID)) {
            return null;
        }
        
        return $this->makeItem($iElementID);
    }

    /**
     * Set clear array to element list
     * @return $this
     */
    public function clear()
    {
        $this->arElementIDList = [];
        return $this->returnClone();
    }

    /**
     * Get element count
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
            return $this->returnClone();
        }

        if(empty($this->arElementIDList)) {
            return $this->returnClone();
        }

        $this->arElementIDList = array_intersect($this->arElementIDList, $arElementIDList);
        return $this->returnClone();
    }

    /**
     * Apply array_merge for element array list
     * @param array $arElementIDList
     * @return $this
     */
    public function merge($arElementIDList)
    {
        if(empty($arElementIDList)) {
            return $this->returnClone();
        }

        if($this->isClear()) {
            $this->arElementIDList = $arElementIDList;
            return $this->returnClone();
        }

        $this->arElementIDList = array_merge($this->arElementIDList, $arElementIDList);
        $this->arElementIDList = array_unique($this->arElementIDList);

        return $this->returnClone();
    }

    /**
     * Get element item list
     *
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
     * Used in "take" method
     * @param $iCount
     * @return $this
     */
    public function skip($iCount)
    {
        $this->iSkip = (int) trim($iCount);
        return $this->returnClone();
    }

    /**
     * Take array with element items
     * @param int $iCount
     * @return array|null|\Lovata\Toolbox\Classes\Item\ElementItem[]
     */
    public function take($iCount)
    {
        $iCount = (int) trim($iCount);
        if(empty($this->arElementIDList) || $iCount < 1) {
            return null;
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
     * Get first element item
     * @return \Lovata\Toolbox\Classes\Item\ElementItem|null
     */
    public function first()
    {
        if(empty($this->arElementIDList)) {
            return null;
        }

        $arElementIDList = array_values($this->arElementIDList);

        $iElementID = $arElementIDList[0];
        return $this->makeItem($iElementID, null);
    }

    /**
     * Get last element item
     * @return \Lovata\Toolbox\Classes\Item\ElementItem|null
     */
    public function last()
    {
        if(empty($this->arElementIDList)) {
            return null;
        }

        $arElementIDList = array_values($this->arElementIDList);
        $iCount = count($arElementIDList);

        $iElementID = $arElementIDList[$iCount -1];
        return $this->makeItem($iElementID, null);
    }

    /**
     * Apply array_shift to element ID list and get first element item
     * @return \Lovata\Toolbox\Classes\Item\ElementItem|null
     */
    public function shift()
    {
        if(empty($this->arElementIDList)) {
            return null;
        }

        $iElementID = array_shift($this->arElementIDList);
        return $this->makeItem($iElementID, null);
    }

    /**
     * Apply array_pop to element ID list and get first element item
     * @return \Lovata\Toolbox\Classes\Item\ElementItem|null
     */
    public function pop()
    {
        if(empty($this->arElementIDList)) {
            return null;
        }

        $iElementID = array_pop($this->arElementIDList);
        return $this->makeItem($iElementID, null);
    }

    /**
     * Save item collection in store
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
     * @param string $sKey
     *
     * @return $this
     */
    public function saved($sKey)
    {
        if(empty($sKey)) {
            return $this;
        }

        $sKey = static::class.'@'.$sKey;

        $obCollection = CollectionStore::instance()->get($sKey);
        if(empty($obCollection)) {
             return $this;
        }

        return clone $obCollection;
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
        $arElementIDList = array_values($this->arElementIDList);
        return isset($arElementIDList[$this->iPosition]);
    }
}