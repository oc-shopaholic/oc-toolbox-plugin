<?php namespace Lovata\Toolbox\Classes\Item;

use Model;
use System\Classes\PluginManager;
use Kharanenka\Helper\CCache;
use October\Rain\Extension\ExtendableTrait;

/**
 * Class ElementItem
 * @package Lovata\Toolbox\Classes\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementItem
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
        if (!empty($this->obElement) && !$this->obElement instanceof Model) {
            $this->obElement = null;
        }

        $this->initActiveLang();
        $this->extendableConstruct();
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
     * @param array  $arParamList
     * @return mixed
     */
    public static function __callStatic($sName, $arParamList)
    {
        return self::extendableCallStatic($sName, $arParamList);
    }


    /**
     * Serialize item object
     * @return array
     */
    public function __sleep()
    {
        return ['iElementID'];
    }

    /**
     * Unserialize object
     */
    public function __wakeup()
    {
        $this->setCachedData();
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toJSON();
    }

    /**
     * @param callable $callback
     */
    public static function extend(callable $callback)
    {
        self::extendableExtendCallback($callback);
    }

    /**
     * Make new element item
     * @see \Lovata\Toolbox\Tests\Unit\ItemTest::testItem()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementItem#makeielementid-obelement--null
     * @param int|string $iElementID
     * @param \Model     $obElement
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
     * @see \Lovata\Toolbox\Tests\Unit\ItemTest::testItem()
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementItem#makenocacheielementid-obelement--null
     * @param int    $iElementID
     * @param \Model $obElement
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
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementItem#clearcacheielementid
     * @param int $iElementID
     */
    public static function clearCache($iElementID)
    {
        if (empty($iElementID)) {
            return;
        }

        CCache::clear(static::getCacheTag(), $iElementID);
    }

    /**
     * Check model data is empty
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementItem#isempty
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->arModelData);
    }

    /**
     * Check model data is not empty
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementItem#isnotempty
     * @return bool
     */
    public function isNotEmpty()
    {
        return !$this->isEmpty();
    }

    /**
     * Get model data
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementItem#toarray
     * @return array
     */
    public function toArray()
    {
        return $this->arModelData;
    }

    /**
     * Get model data in JSON string
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementItem#tojson
     * @return string
     */
    public function toJSON()
    {
        return json_encode($this->arModelData);
    }

    /**
     * Get model object
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementItem#getobject
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
        if (empty($this->obElement)) {
            return;
        }

        //Set default lang (if update cache with non default lang)
        if (self::$bLangInit && !empty(self::$sDefaultLang) && $this->obElement->isClassExtendedWith('RainLab.Translate.Behaviors.TranslatableModel')) {
            $this->obElement->lang(self::$sDefaultLang);
        }

        $arResult = $this->getElementData();
        if (empty($arResult)) {
            return;
        }

        $this->arModelData = $arResult;

        //Save lang properties (integration with Translate plugin)
        $this->setLangProperties();

        //Run methods from $arExtendResult array
        $this->setExtendData();
    }

    /**
     * Run methods from $arExtendResult array
     */
    protected function setExtendData()
    {
        //Check extend result methods
        if (empty($this->arExtendResult)) {
            return;
        }

        //Apply extend methods
        foreach ($this->arExtendResult as $sMethodName) {
            if (empty($sMethodName) || !(method_exists($this, $sMethodName) || $this->methodExists($sMethodName))) {
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
        if (empty($this->iElementID)) {
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
        if (empty($this->iElementID)) {
            return;
        }

        $arCacheTags = static::getCacheTag();
        $sCacheKey = $this->iElementID;

        $this->arModelData = CCache::get($arCacheTags, $sCacheKey);
        if (!$this->isEmpty()) {
            return;
        }

        $this->setData();

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $this->arModelData);
    }

    /**
     * Get and save active lang list
     */
    protected function getActiveLangList()
    {
        if (self::$arActiveLangList !== null || !PluginManager::instance()->hasPlugin('RainLab.Translate')) {
            return self::$arActiveLangList;
        }

        self::$arActiveLangList = \RainLab\Translate\Models\Locale::isEnabled()->lists('code');
        if (empty(self::$arActiveLangList)) {
            return self::$arActiveLangList;
        }

        //Remove default lang from list
        foreach (self::$arActiveLangList as $iKey => $sLangCode) {
            if ($sLangCode == self::$sDefaultLang) {
                unset(self::$arActiveLangList[$iKey]);
                break;
            }
        }

        return self::$arActiveLangList;
    }

    /**
     * Set model data from object
     * @return mixed
     */
    abstract protected function getElementData();

    /**
     * Set element object
     * @return mixed
     */
    abstract protected function setElementObject();

    /**
     * Get cache tag array for model
     * @return array
     */
    abstract protected static function getCacheTag();

    /**
     * Process translatable fields and save values, how 'field_name|lang_code'
     */
    private function setLangProperties()
    {
        if (empty($this->obElement) || !$this->obElement->isClassExtendedWith('RainLab.Translate.Behaviors.TranslatableModel')) {
            return;
        }

        //Check translate model property
        if (empty($this->obElement->translatable) || !is_array($this->obElement->translatable)) {
            return;
        }

        //Get active lang list from Translate plugin
        $arLangList = self::getActiveLangList();
        if (empty($arLangList)) {
            return;
        }

        //Process translatable fields
        foreach ($this->obElement->translatable as $sField) {
            //Check field name
            if (empty($sField) || !is_string($sField)) {
                continue;
            }

            if (!isset($this->arModelData[$sField])) {
                continue;
            }

            //Save field value with different lang code
            foreach ($arLangList as $sLangCode) {
                $sLangField = $sField.'|'.$sLangCode;
                $sValue = $this->obElement->lang($sLangCode)->$sField;
                $this->setAttribute($sLangField, $sValue);
            }
        }
    }
}
