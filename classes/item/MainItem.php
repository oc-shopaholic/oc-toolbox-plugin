<?php namespace Lovata\Toolbox\Classes\Item;

use System\Models\File;

use Lovata\Toolbox\Classes\Collection\ElementCollection;
use Lovata\Toolbox\Traits\Helpers\TraitInitActiveLang;

/**
 * Class MainItem
 * @package Lovata\Toolbox\Classes\Item
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Extension\ExtendableTrait
 */
abstract class MainItem
{
    use TraitInitActiveLang;

    /** @var array Array with model data */
    protected $arModelData = [];

    /** @var array */
    public $arRelationList = [];

    /**
     * Get param from model data
     * @param string $sName
     * @return mixed|null
     */
    public function __get($sName)
    {
        //Get relation field value
        if (!empty($this->arRelationList) && isset($this->arRelationList[$sName])) {
            return $this->getRelationField($sName, $this->arRelationList[$sName]);
        }

        $sMethodName = 'get'.studly_case($sName).'Attribute';
        if (method_exists(static::class, $sMethodName) || $this->methodExists($sMethodName)) {
            return $this->$sMethodName();
        }

        $sAttachOneField = 'attachOne|'.$sName;
        if (isset($this->arModelData[$sAttachOneField])) {
            return $this->getUploadFileField($sName, $sAttachOneField);
        }

        $sAttachManyField = 'attachMany|'.$sName;
        if (isset($this->arModelData[$sAttachManyField])) {
            return $this->getUploadFileListField($sName, $sAttachManyField);
        }

        if (!empty(self::$sActiveLang)) {
            return $this->getLangAttribute($sName);
        }

        return $this->getAttribute($sName);
    }

    /**
     * Get attribute value
     * @param string $sName
     * @return mixed|null
     */
    public function getAttribute($sName)
    {
        if (empty($sName)) {
            return null;
        }

        if (!empty($this->arModelData) && isset($this->arModelData[$sName])) {
            return $this->arModelData[$sName];
        }

        return null;
    }

    /**
     * Get lang attribute value
     * @param string $sName
     * @param string $sLangCode
     * @return mixed|null
     */
    public function getLangAttribute($sName, $sLangCode = null)
    {
        if (empty($sName)) {
            return null;
        }

        if (empty($sLangCode)) {
            $sLangCode = self::$sActiveLang;
        }

        if (empty($sLangCode)) {
            return $this->getAttribute($sName);
        }

        $sLangName = $sName.'|'.$sLangCode;
        if (!empty($this->arModelData) && isset($this->arModelData[$sLangName])) {
            return $this->arModelData[$sLangName];
        }

        return $this->getAttribute($sName);
    }

    /**
     * Set attribute value
     * @param string $sField
     * @param mixed  $obValue
     */
    public function __set($sField, $obValue)
    {
        $this->setAttribute($sField, $obValue);
    }

    /**
     * Set attribute value
     * @param string $sField
     * @param mixed  $obValue
     */
    public function setAttribute($sField, $obValue)
    {
        if (empty($sField)) {
            return;
        }

        $this->arModelData[$sField] = $obValue;
    }

    /**
     * @param string $sName
     * @param array  $arParamList
     * @return mixed|null
     */
    public function __call($sName, $arParamList)
    {
        return $this->$sName;
    }

    /**
     * @param string $sName
     * @return bool
     */
    public function __isset($sName)
    {
        $sValue = $this->getAttribute($sName);

        return !empty($sValue);
    }

    /**
     * Get "Has one" item object or get "Has many" collection object
     * @param string $sName
     * @param array  $arRelationData
     *
     * @return null|ElementItem|\Lovata\Toolbox\Classes\Collection\ElementCollection
     */
    protected function getRelationField($sName, $arRelationData)
    {
        //Check relation config data
        if (empty($sName) || empty($arRelationData) || !is_array($arRelationData)) {
            return null;
        }

        if (empty($arRelationData['class']) || empty($arRelationData['field'])) {
            return null;
        }

        $sClassName = $arRelationData['class'];
        $sFieldName = $arRelationData['field'];

        //Check class is exist
        if (!class_exists($sClassName)) {
            return null;
        }

        $obValue = $this->getAttribute($sName);
        if (!empty($obValue) && $obValue instanceof $sClassName) {
            return $obValue;
        }

        $obValue = $sClassName::make($this->$sFieldName);
        if ($obValue instanceof ElementCollection && empty($this->$sFieldName)) {
            $obValue->intersect($this->$sFieldName);
        }

        $this->setAttribute($sName, $obValue);

        return $this->getAttribute($sName);
    }

    /**
     * Get image object form field with image array
     * @param string $sField
     * @param string $sFakeField
     *
     * @return File|null
     */
    protected function getUploadFileField($sField, $sFakeField)
    {
        $obFile = $this->getAttribute($sField);
        if (!empty($obFile)) {
            return $obFile;
        }

        $arFileData = $this->getAttribute($sFakeField);
        $obFile = $this->initUploadFileObject($arFileData);
        $this->setAttribute($sField, $obFile);

        return $obFile;
    }

    /**
     * Get image object form field with image array
     * @param string $sField
     * @param string $sFakeField
     * @return File[]|null
     */
    protected function getUploadFileListField($sField, $sFakeField)
    {
        $arFileList = $this->getAttribute($sField);
        if (!empty($arFileList)) {
            return $arFileList;
        }

        $arFileList = [];

        $arCachedFileList = (array) $this->getAttribute($sFakeField);
        foreach ($arCachedFileList as $arFileData) {
            $obFile = $this->initUploadFileObject($arFileData);
            if (empty($obFile)) {
                continue;
            }

            $arFileList[] = $obFile;
        }

        $this->setAttribute($sField, $arFileList);

        return $arFileList;
    }

    /**
     * @param array $arFileData
     * @return File|null
     */
    protected function initUploadFileObject($arFileData)
    {
        if (empty($arFileData)) {
            return null;
        }

        $obFile = File::make($arFileData);
        $obFile->disk_name = array_get($arFileData, 'disk_name');
        if (!empty(self::$sActiveLang) && self::$sActiveLang != self::$sDefaultLang && $obFile->isClassExtendedWith('RainLab.Translate.Behaviors.TranslatableModel')) {
            foreach ($obFile->translatable as $sLangField) {
                $obFile->$sLangField = array_get($arFileData, self::$sActiveLang.'.'.$sLangField, $obFile->$sLangField);
            }
        }

        return $obFile;
    }
}
