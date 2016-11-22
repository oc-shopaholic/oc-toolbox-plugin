<?php namespace Kharanenka\Helper;

use Lang;

/**
 * Class CustomValidationMessage
 * @package Kharanenka\Helper;
 * @author Andrey Kharanenka, kharanenka@gmail.com
 * 
 * @property array $customMessages
 * @property array $attributeNames
 */
trait CustomValidationMessage {
    
    /**
     * Set validation custom messages
     * @param string $sPluginName
     * @param array $arRulesList
     */
    protected function setCustomMessage($sPluginName, $arRulesList) {

        if(empty($sPluginName) || empty($arRulesList)) {
            return;
        }
        
        foreach($arRulesList as $sRuleName) {
            if(!isset($this->customMessages[$sRuleName])) {
                $this->customMessages[$sRuleName] = Lang::get('lovata.'.$sPluginName.'::lang.validation.'.$sRuleName);
            }
        }
    }

    /**
     * Set validation custom attribute names
     * @param $sPluginName
     * @param array $arFieldsList
     */
    protected function setCustomAttributeName($sPluginName, $arFieldsList) {

        if(empty($sPluginName) || empty($arFieldsList)) {
            return;
        }

        foreach($arFieldsList as $sName) {
            if(empty($sName)) {
                continue;
            }

            if(!isset($this->attributeNames[$sName])) {
                $this->attributeNames[$sName] = Lang::get('lovata.'.$sPluginName.'::lang.field.'.$sName);
            }
        }
    }
}