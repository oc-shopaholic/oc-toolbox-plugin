<?php namespace Lovata\Toolbox\Classes\Item;

use Model;
use Kharanenka\Helper\CCache;
use October\Rain\Extension\ExtendableTrait;

/**
 * Class ElementItem
 * @package Lovata\Toolbox\Classes\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class ElementItem extends MainItem
{
    use ExtendableTrait;
    
    public $implement = [];
    
    /** @var int */
    protected $iElementID = null;

    /** @var \Model */
    protected $obElement = null;

    /** @var array */
    public $arExtendResult = [];

    /**
     * ElementItem constructor.
     * @param int    $iElementID
     * @param \Model $obElement
     */
    public function __construct($iElementID, $obElement)
    {
        $this->iElementID = $iElementID;
        $this->obElement = $obElement;

        //Check instance of obElement
        if(!empty($this->obElement) && !$this->obElement instanceof Model) {
            $this->obElement = null;
        }

        $this->extendableConstruct();
    }

    /**
     * Set model data from object
     * @return mixed
     */
    protected abstract function getElementData();

    /**
     * Set element object
     * @return mixed
     */
    protected abstract function setElementObject();

    /**
     * Get cache tag array for model
     * @return array
     */
    protected static abstract function getCacheTag();


    /**
     * Make new element item
     * @param int              $iElementID
     * @param \Model           $obElement
     *
     * @return $this
     */
    public static function make($iElementID, $obElement = null)
    {
        $arParamList = [
            'iElementID' => $iElementID,
            'obElement'  => $obElement,
        ];

        /** @var ElementItem $obItem */
        $obItem = app()->make(static::class, $arParamList);

        //Init cached array model data
        $obItem->setCachedData();

        return $obItem;
    }

    /**
     * Make new element item (no cache)
     * @param int              $iElementID
     * @param \Model           $obElement
     *
     * @return $this
     */
    public static function makeNoCache($iElementID, $obElement = null)
    {
        $arParamList = [
            'iElementID' => $iElementID,
            'obElement'  => $obElement,
        ];

        /** @var ElementItem $obItem */
        $obItem = app()->make(static::class, $arParamList);

        //Init array model data (no cache)
        $obItem->setData();

        return $obItem;
    }

    /**
     * Remove model data from cache
     * @param int $iElementID
     */
    public static function clearCache($iElementID)
    {
        if(empty($iElementID)) {
            return;
        }

        CCache::clear(static::getCacheTag(), $iElementID);
    }

    /**
     * Check model data is empty
     * @return bool
     */
    public function isEmpty()
    {
        //Check active flag, if item has trait TraitCheckItemActive
        if(method_exists($this, 'isActive') && !$this->isActive()) {
            return true;
        }

        //Check trashed flag, if item has trait TraitCheckItemTrashed
        if(method_exists($this, 'isTrashed') && $this->isTrashed()) {
            return true;
        }

        return empty($this->arModelData);
    }

    /**
     * Get model data
     * @return array
     */
    public function toArray()
    {
        return $this->arModelData;
    }

    /**
     * Get model data in JSON string
     * @return string
     */
    public function toJSON()
    {
        return json_encode($this->arModelData);
    }

    /**
     * Get model object
     *
     * @return \Model
     */
    public function getObject()
    {
        $this->setElementObject();
        return $this->obElement;
    }

    /**
     * Set data from model object
     */
    protected function setData()
    {
        $this->setElementObject();
        if(empty($this->obElement)) {
            return;
        }

        $arResult = $this->getElementData();
        if(empty($arResult)) {
            return;
        }

        $this->arModelData = $arResult;

        //Check extend result methods
        if(empty($this->arExtendResult)) {
            return;
        }

        //Apply extend methods
        foreach ($this->arExtendResult as $sMethodName) {
            if(empty($sMethodName) || !(method_exists($this, $sMethodName) || $this->methodExists($sMethodName))) {
                continue;
            }

            $this->$sMethodName();
        }
    }

    /**
     * Set cached brand data
     */
    protected function setCachedData()
    {
        if(empty($this->iElementID)) {
            return;
        }

        //Set model data from cache
        $this->setDataFromCache();
    }

    /**
     * Set model data from cache
     */
    protected function setDataFromCache()
    {
        if(empty($this->iElementID)) {
            return;
        }

        $arCacheTags = static::getCacheTag();
        $sCacheKey = $this->iElementID;

        $this->arModelData = CCache::get($arCacheTags, $sCacheKey);
        if(!$this->isEmpty()) {
            return;
        }

        $this->setData();

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $this->arModelData);
    }

    /**
     * @param string $sName
     * @return string
     */
    public function __get($sName)
    {
        return $this->extendableGet($sName);
    }

    /**
     * @param string $sName
     * @param mixed  $obValue
     */
    public function __set($sName, $obValue)
    {
        $this->extendableSet($sName, $obValue);
    }

    /**
     * @param string $sName
     * @param array  $arParamList
     * @return mixed
     */
    public function __call($sName, $arParamList)
    {
        return $this->extendableCall($sName, $arParamList);
    }

    /**
     * @param string $sName
     * @param array $arParamList
     * @return mixed
     */
    public static function __callStatic($sName, $arParamList)
    {
        return self::extendableCallStatic($sName, $arParamList);
    }

    /**
     * @param callable $callback
     */
    public static function extend(callable $callback)
    {
        self::extendableExtendCallback($callback);
    }
}