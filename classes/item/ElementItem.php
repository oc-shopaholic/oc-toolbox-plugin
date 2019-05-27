<?php namespace Lovata\Toolbox\Classes\Item;

use Model;
use October\Rain\Extension\ExtendableTrait;

use Kharanenka\Helper\CCache;

/**
 * Class ElementItem
 * @package Lovata\Toolbox\Classes\Item
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @link    https://github.com/lovata/oc-toolbox-plugin/wiki/ElementItem
 */
abstract class ElementItem extends MainItem
{
    use ExtendableTrait;

    const MODEL_CLASS = Model::class;

    public $implement = [];

    /** @var int */
    protected $iElementID = null;

    /** @var \Model */
    protected $obElement = null;

    /** @var array */
    protected static $arBooted = [];

    /** @var array */
    public $arExtendResult = [];

    /**
     * ElementItem constructor.
     * @param int    $iElementID
     * @param \Model $obElement
     * @throws \Exception
     */
    public function __construct($iElementID, $obElement)
    {
        $this->iElementID = $iElementID;
        $this->obElement = $obElement;

        //Check instance of obElement
        $sModelClass = static::MODEL_CLASS;
        if (!empty($this->obElement) && !$this->obElement instanceof $sModelClass) {
            $this->obElement = null;
        }

        $this->bootIfNotBooted();

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
     * @throws \Exception
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
     * @param int|string $iElementID
     * @param \Model     $obElement
     *
     * @return $this
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementItem#makeielementid-obelement--null
     * @see  \Lovata\Toolbox\Tests\Unit\ItemTest::testItem()
     */
    public static function make($iElementID, $obElement = null)
    {
        $arParamList = [
            'iElementID' => $iElementID,
            'obElement'  => $obElement,
        ];

        $obItem = ItemStorage::get(static::class, $iElementID);
        if (!empty($obItem)) {
            return $obItem;
        }

        /** @var ElementItem $obItem */
        $obItem = app()->make(static::class, $arParamList);

        //Init cached array model data
        $obItem->setCachedData();

        ItemStorage::set(static::class, $iElementID, $obItem);

        return $obItem;
    }

    /**
     * Make new element item (no cache)
     * @param int    $iElementID
     * @param \Model $obElement
     *
     * @return $this
     * @link https://github.com/lovata/oc-toolbox-plugin/wiki/ElementItem#makenocacheielementid-obelement--null
     * @see  \Lovata\Toolbox\Tests\Unit\ItemTest::testItem()
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

        ItemStorage::clear(static::class, $iElementID);
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
        $this->initElementObject();

        return $this->obElement;
    }

    /**
     * Set data from model object
     */
    protected function setData()
    {
        $this->initElementObject();
        if (empty($this->obElement)) {
            return;
        }

        //Set default lang (if update cache with non default lang)
        if (self::$bLangInit && !empty(self::$sDefaultLang) && $this->obElement->isClassExtendedWith('RainLab.Translate.Behaviors.TranslatableModel')) {
            $this->obElement->lang(self::$sDefaultLang);
        }

        //Get cached field list from model and add fields to cache array
        $this->setCachedFieldList();

        //Get element data
        $arResult = $this->getElementData();
        if (empty($arResult) || !is_array($arResult)) {
            $arResult = [];
        }

        //Add fields values to cached array
        foreach ($arResult as $sField => $sValue) {
            $this->setAttribute($sField, $sValue);
        }

        //Save lang properties (integration with Translate plugin)
        $this->setLangProperties();

        //Run methods from $arExtendResult array
        $this->setExtendData();
    }

    /**
     * Get cached field list from model and add fields to cache array
     */
    protected function setCachedFieldList()
    {
        if (!method_exists($this->obElement, 'getCachedField')) {
            return;
        }

        //Get cached field list
        $arFieldList = $this->obElement->getCachedField();
        if (empty($arFieldList)) {
            return;
        }

        foreach ($arFieldList as $sField) {
            if (array_key_exists($sField, (array) $this->obElement->attachOne)) {
                $arFileData = $this->getUploadFileData($this->obElement->$sField);
                $sFieldName = 'attachOne|'.$sField;
                $this->setAttribute($sFieldName, $arFileData);
            } elseif (array_key_exists($sField, (array) $this->obElement->attachMany)) {
                $arFileList = [];
                $obFileList = $this->obElement->$sField;
                foreach ($obFileList as $obFile) {
                    $arFileData = $this->getUploadFileData($obFile);
                    $arFileList[] = $arFileData;
                }

                $sFieldName = 'attachMany|'.$sField;
                $this->setAttribute($sFieldName, $arFileList);
            } else {
                $this->setAttribute($sField, $this->obElement->$sField);
            }
        }
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
     * Set model data from object
     * @return mixed
     */
    protected function getElementData()
    {
        return [];
    }

    /**
     * Check if the model needs to be booted and if so, do it.
     *
     * @return void
     */
    protected function bootIfNotBooted()
    {
        if (isset(static::$arBooted[static::class])) {
            return;
        }

        static::boot();
        static::$arBooted[static::class] = true;
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        static::bootTraits();
    }

    /**
     * Boot all of the bootable traits on the model.
     *
     * @return void
     */
    protected static function bootTraits()
    {
        foreach (class_uses_recursive(get_called_class()) as $trait) {
            if (method_exists(get_called_class(), $method = 'boot'.class_basename($trait))) {
                forward_static_call([get_called_class(), $method]);
            }
        }
    }

    /**
     * Init element object
     */
    protected function initElementObject()
    {
        $sModelClass = static::MODEL_CLASS;
        if (!empty($this->obElement) && !$this->obElement instanceof $sModelClass) {
            $this->obElement = null;
        }

        if (!empty($this->obElement) || empty($this->iElementID)) {
            return;
        }

        $this->setElementObject();
    }

    /**
     * Set element object
     */
    protected function setElementObject()
    {
        $sModelClass = static::MODEL_CLASS;
        $this->obElement = $sModelClass::find($this->iElementID);
    }

    /**
     * Get cache tag array for model
     * @return array
     */
    protected static function getCacheTag()
    {
        return [static::class];
    }

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
            if (empty($sField) || (!is_string($sField) && !is_array($sField))) {
                continue;
            }

            if (is_array($sField)) {
                $sField = array_shift($sField);
            }

            if (!isset($this->arModelData[$sField]) || array_key_exists($sField, (array) $this->obElement->attachOne) || array_key_exists($sField, (array) $this->obElement->attachMany)) {
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

    /**
     * Get image data from image object
     * @param \System\Models\File $obFile
     * @return []
     */
    private function getUploadFileData($obFile) : array
    {
        if (empty($obFile)) {
            return [];
        }

        //Set default lang in image object
        if (!empty(self::$sDefaultLang) && $obFile->isClassExtendedWith('RainLab.Translate.Behaviors.TranslatableModel')) {
            $obFile->lang(self::$sDefaultLang);
        }

        //Convert image data to array
        $arFileData = $obFile->toArray();
        $arLangList = $this->getActiveLangList();
        if (empty($arLangList) || !$obFile->isClassExtendedWith('RainLab.Translate.Behaviors.TranslatableModel')) {
            return $arFileData;
        }

        //Add lang fields to array
        foreach ($arLangList as $sLangCode) {
            $arFileData[$sLangCode] = [];
            foreach ($obFile->translatable as $sLangField) {
                $arFileData[$sLangCode][$sLangField] = $obFile->lang($sLangCode)->$sLangField;
            }
        }

        return $arFileData;
    }
}
