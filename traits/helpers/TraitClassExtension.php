<?php namespace Lovata\Toolbox\Traits\Helpers;

/**
 * Class TraitClassExtension
 * @package Lovata\Toolbox\Traits\Helpers
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 */
trait TraitClassExtension
{
    /**
     * @var array
     */
    public static $arExtensionMethodResult = [];

    /**
     * @param string $sMethodName
     * @param array $arResult
     * @param array $arData
     */
    protected static function extendMethodResult($sMethodName, $arResult, $arData = [])
    {
        if(empty(static::$arExtensionMethodResult) || empty($sMethodName)) {
            return;
        }

        //Get method list
        if(!isset(static::$arExtensionMethodResult[$sMethodName]) || empty(static::$arExtensionMethodResult[$sMethodName])) {
            return;
        }

        $arMethodList = static::$arExtensionMethodResult[$sMethodName];
        foreach($arMethodList as $arMethodData) {

            //Check method data
            if(empty($arMethodData) || empty($arMethodData['class']) || empty($arMethodData['method'])) {
                continue;
            }

            //Get class name and method name
            $sClassName = $arMethodData['class'];
            $sMethodName = $arMethodData['method'];

            //Check if class and method exists
            if(!class_exists($sClassName) || !method_exists($sClassName, $sMethodName)) {
                continue;
            }

            //Generate method param array for call_user_func_array function
            $arMethodParams = [];
            $arMethodParams[] = $arResult;
            if(!empty($arData)) {
                $arMethodParams = array_merge($arMethodParams, $arData);
            }

            //Call method
            $arResult = call_user_func_array([$sClassName, $sMethodName], $arMethodParams);
        }
    }
}