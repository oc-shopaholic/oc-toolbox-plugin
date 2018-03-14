<?php namespace Lovata\Toolbox\Classes\Storage;

/**
 * Class AbstractUserStorage
 * @package Lovata\Toolbox\Classes\Storage
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractUserStorage
{
    /**
     * Get value from storage
     * @param string $sKey
     * @param mixed  $sDefaultValue
     *
     * @return mixed
     */
    abstract public function get($sKey, $sDefaultValue = null);

    /**
     * Put value to storage
     * @param string $sKey
     * @param mixed  $obValue
     */
    abstract public function put($sKey, $obValue);

    /**
     * Clear value in storage
     * @param string $sKey
     */
    abstract public function clear($sKey);

    /**
     * Get list value
     * @param string $sKey
     * @return array
     */
    public function getList($sKey)
    {
        if (empty($sKey)) {
            return [];
        }

        //Get value from storage
        $arValueList = $this->get($sKey);
        if (empty($arValueList) || !is_array($arValueList)) {
            $arValueList = [];
        }

        return $arValueList;
    }

    /**
     * Add value to list
     * @param string $sKey
     * @param string $sValue
     */
    public function addToList($sKey, $sValue)
    {
        if (empty($sKey) || empty($sValue)) {
            return;
        }

        //Get value from storage
        $arValueList = $this->getList($sKey);

        array_unshift($arValueList, $sValue);
        $arValueList = array_unique($arValueList);

        $this->put($sKey, $arValueList);
    }

    /**
     * Remove value from list
     * @param string $sKey
     * @param string $sValue
     */
    public function removeFromList($sKey, $sValue)
    {
        if (empty($sKey) || empty($sValue)) {
            return;
        }

        //Get value from storage
        $arValueList = $this->getList($sKey);

        $iPosition = array_search($sValue, $arValueList);
        if ($iPosition === false) {
            return;
        }

        unset($arValueList[$iPosition]);
        $arValueList = array_values($arValueList);

        $this->put($sKey, $arValueList);
    }
}
