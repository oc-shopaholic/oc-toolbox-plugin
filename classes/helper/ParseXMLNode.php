<?php namespace Lovata\Toolbox\Classes\Helper;

/**
 * Class ParseXMLNode
 * @package Lovata\Toolbox\Classes\Helper
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ParseXMLNode
{
    /** @var array */
    protected $arImportData = [];

    /** @var ImportXMLNode */
    protected $obElementNode;

    protected $arImportSettings = [];

    /**
     * @param ImportXMLNode $obNode
     * @param array $arSettings
     */
    public function __construct($obNode, $arSettings)
    {
        $this->obElementNode = $obNode;
        $this->arImportSettings = $arSettings;

        $this->parse();
    }

    /**
     * @return ImportXMLNode
     */
    public function getNode()
    {
        return $this->obElementNode;
    }

    /**
     * Get node data
     * @return array
     */
    public function get()
    {
        return $this->arImportData;
    }

    protected function parse()
    {
        if (empty($this->arImportSettings)) {
            return;
        }

        foreach ($this->arImportSettings as $arFieldData) {
            $sFieldName = array_get($arFieldData, 'field');
            $sFieldPath = array_get($arFieldData, 'path_to_field');
            if (empty($sFieldName) || empty($sFieldPath)) {
                continue;
            }

            $sMethodName = 'parse'.studly_case($sFieldName).'Attribute';
            if (method_exists(static::class, $sMethodName)) {
                $sValue = $this->$sMethodName($sFieldPath);
            } else {
                $sValue = $this->obElementNode->getValueByPath($sFieldPath);
            }

            if ($sValue === null) {
                continue;
            }

            $sCurrentValue = array_get($this->arImportData, $sFieldName);
            if (!empty($sCurrentValue) && !is_array($sCurrentValue)) {
                $sCurrentValue = [$sCurrentValue];
            }

            if (is_array($sCurrentValue) && is_array($sValue)) {
                $sCurrentValue = array_merge($sCurrentValue, $sValue);
                $sCurrentValue = array_filter($sCurrentValue);
                $sCurrentValue = array_unique($sCurrentValue);
            } elseif (is_array($sCurrentValue) && !is_array($sValue)) {
                $sCurrentValue[] = $sValue;
                $sCurrentValue = array_filter($sCurrentValue);
                $sCurrentValue = array_unique($sCurrentValue);
            } else {
                $sCurrentValue = $sValue;
            }

            array_set($this->arImportData, $sFieldName, $sCurrentValue);
        }
    }
}