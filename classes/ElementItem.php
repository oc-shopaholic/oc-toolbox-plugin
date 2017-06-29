<?php namespace Lovata\Toolbox\Classes;

use App;
use Kharanenka\Helper\CCache;
use Lovata\Toolbox\Traits\Helpers\TraitClassExtension;
use October\Rain\Support\Collection;

/**
 * Class ElementItem
 * @package Lovata\Toolbox\Classes
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class ElementItem
{
    use TraitClassExtension;

    /** @var array Array with model data */
    protected $arModelData = [];

    /** @var int */
    protected $iElementID = null;

    /** @var \Model */
    protected $obElement = null;

    /** @var Collection */
    protected $obSettings = [];

    /**
     * ElementItem constructor.
     * @param int    $iElementID
     * @param \Model $obElement
     * @param array  $arSettings
     */
    public function __construct($iElementID, $obElement, $arSettings)
    {
        $this->iElementID = $iElementID;
        $this->obElement = $obElement;
        $this->obSettings = $arSettings;

        //Make collection from settings
        if(is_array($this->obSettings)) {
            $this->obSettings = Collection::make($this->obSettings);
        }
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
     * Get param from model data
     * @param string $sName
     * @return mixed|null
     */
    public function __get($sName)
    {
        if(!empty($this->arModelData) && isset($this->arModelData[$sName])) {
            return $this->arModelData[$sName];
        }

        if(!empty($this->obElement)) {
            return $this->obElement->$sName;
        }

        return null;
    }

    /**
     * @param string $sName
     * @param array $arParamList
     * @return mixed|null
     */
    public function __call($sName, $arParamList)
    {
        return $this->$sName;
    }

    /**
     * Make new element item
     * @param int              $iElementID
     * @param \Model           $obElement
     * @param array|Collection $arSettings
     *
     * @return $this
     */
    public static function make($iElementID, $obElement = null, $arSettings = null)
    {
        $arParamList = [
            'iElementID' => $iElementID,
            'obElement'  => $obElement,
            'arSettings' => $arSettings,
        ];

        /** @var ElementItem $obStore */
        $obStore = App::make(static::class, $arParamList);

        //Init cached array model data
        $obStore->setCachedData();

        return $obStore;
    }

    /**
     * Make new element item (no cache)
     * @param int              $iElementID
     * @param \Model           $obElement
     * @param array|Collection $arSettings
     *
     * @return $this
     */
    public static function makeNoCache($iElementID, $obElement, $arSettings = null)
    {
        $arParamList = [
            'iElementID' => $iElementID,
            'obElement'  => $obElement,
            'arSettings' => $arSettings,
        ];

        /** @var ElementItem $obStore */
        $obStore = App::make(static::class, $arParamList);

        //Init array model data (no cache)
        $obStore->setData();

        return $obStore;
    }

    /**
     * Check model data is empty
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->arModelData);
    }

    /**
     * Get model data
     * @return array
     */
    public function getArray()
    {
        return $this->arModelData;
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
        if(!empty($this->arModelData)) {
            return;
        }

        $this->setData();

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $this->arModelData);
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

        self::extendMethodResult(__FUNCTION__, $arResult, [$this->obElement]);
        $this->arModelData = $arResult;
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
        self::extendMethodResult(__FUNCTION__, $this->arModelData);
    }
}