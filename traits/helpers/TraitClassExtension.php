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
    protected static $arExtensionMethodResult = [];

    /**
     * @param string $sMainMethodName
     * @param array $arResult
     * @param array $arData
     */
    protected static function extendMethodResult($sMainMethodName, &$arResult, $arData = [])
    {
        if(empty(static::$arExtensionMethodResult) || empty($sMainMethodName)) {
            return;
        }

        //Get method list
        if(!isset(static::$arExtensionMethodResult[$sMainMethodName]) || empty(static::$arExtensionMethodResult[$sMainMethodName])) {
            return;
        }

        $arMethodList = static::$arExtensionMethodResult[$sMainMethodName];
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

    /**
     * Add extend method to list
     * @param string $sMainMethodName
     * @param string $sClassName
     * @param string $sMethodName
     */
    public static function addExtendResultMethod($sMainMethodName, $sClassName, $sMethodName)
    {
        //Check params
        if(empty($sMainMethodName) || empty($sClassName) || empty($sMethodName)) {
            return;
        }

        //Create for main method data array
        if(empty(static::$arExtensionMethodResult) || !isset(static::$arExtensionMethodResult[$sMainMethodName])) {
            static::$arExtensionMethodResult[$sMainMethodName] = [];
        }

        //Check method on duplicate
        foreach(static::$arExtensionMethodResult[$sMainMethodName] as $arMethodData) {
            if($arMethodData['class'] == $sClassName && $arMethodData['method'] == $sMethodName) {
                return;
            }
        }

        //Add method data on list
        static::$arExtensionMethodResult[$sMainMethodName][] = [
            'class' => $sClassName,
            'method' => $sMethodName,
        ];
    }
}