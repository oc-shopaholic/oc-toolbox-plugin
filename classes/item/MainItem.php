<?php namespace Lovata\Toolbox\Classes\Item;

/**
 * Class MainItem
 * @package Lovata\Toolbox\Classes\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * 
 * @mixin \October\Rain\Extension\ExtendableTrait
 */
abstract class MainItem
{
    /** @var array Array with model data */
    protected $arModelData = [];

    /** @var array  */
    public $arRelationList = [];

    /**
     * Get param from model data
     * @param string $sName
     * @return mixed|null
     */
    public function __get($sName)
    {
        //Check active flag, if item has trait TraitCheckItemActive
        if(method_exists($this, 'isActive') && !$this->isActive()) {
            return null;
        }

        //Check trashed flag, if item has trait TraitCheckItemTrashed
        if(method_exists($this, 'isTrashed') && $this->isTrashed()) {
            return null;
        }

        //Get relation field value
        if(!empty($this->arRelationList) && isset($this->arRelationList[$sName])) {
            return $this->getRelationField($this->arRelationList[$sName]);
        }

        $sMethodName = 'get'.studly_case($sName).'Attribute';
        if(method_exists(static::class, $sMethodName) || $this->methodExists($sMethodName)) {
            return $this->$sMethodName();
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
        if(empty($sName)) {
            return null;
        }

        if(!empty($this->arModelData) && isset($this->arModelData[$sName])) {
            return $this->arModelData[$sName];
        }

        return null;
    }

    /**
     * Set attribute value
     * @param string $sField
     * @param mixed $obValue
     */
    public function __set($sField, $obValue)
    {
        $this->setAttribute($sField, $obValue);
    }

    /**
     * Set attribute value
     * @param string $sField
     * @param mixed $obValue
     */
    public function setAttribute($sField, $obValue)
    {
        if(empty($sField)) {
            return;
        }

        $this->arModelData[$sField] = $obValue;
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
     * @param array $arRelationData
     *
     * @return null|ElementItem|\Lovata\Toolbox\Classes\Collection\ElementCollection
     */
    protected function getRelationField($arRelationData)
    {
        //Check relation config data
        if(empty($arRelationData) || !is_array($arRelationData)) {
            return null;
        }

        if(empty($arRelationData['class']) || empty($arRelationData['field'])) {
            return null;
        }

        $sClassName = $arRelationData['class'];
        $sFieldName = $arRelationData['field'];

        //Check class is exist
        if(!class_exists($sClassName)) {
            return null;
        }

        $obValue = $this->getAttribute($sFieldName);
        if(!empty($obValue) && $obValue instanceof $sClassName) {
            return $obValue;
        }

        $this->$sFieldName = $sClassName::make($this->$sFieldName);
        return $this->getAttribute($sFieldName);
    }
}