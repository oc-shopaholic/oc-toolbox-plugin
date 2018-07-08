<?php namespace Lovata\Toolbox\Classes\Storage;

use Lovata\Toolbox\Classes\Helper\UserHelper;

/**
 * Class UserStorage
 * @package Lovata\Toolbox\Classes\Storage
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class UserStorage extends AbstractUserStorage
{
    /** @var SessionUserStorage|CookieUserStorage */
    protected $obDefaultStorage;

    /**
     * Set default user storage
     * @param string $obUserStorage
     * @param int    $iMinutePeriod
     */
    public function setDefaultStorage($obUserStorage, $iMinutePeriod = 1440)
    {
        if (empty($obUserStorage)) {
            return;
        }

        $this->obDefaultStorage = app($obUserStorage);
        if (!empty($this->obDefaultStorage) && $this->obDefaultStorage instanceof CookieUserStorage) {
            $this->obDefaultStorage->setMinutePeriod($iMinutePeriod);
        }
    }

    /**
     * Get value from storage
     * @param string $sKey
     * @param mixed  $sDefaultValue
     *
     * @return mixed
     */
    public function get($sKey, $sDefaultValue = null)
    {
        if (empty($sKey)) {
            return $sDefaultValue;
        }

        //Get auth user object
        $obUser = UserHelper::instance()->getUser();
        if (empty($obUser)) {
            return $this->getDefaultStorageValue($sKey, $sDefaultValue);
        }

        //Get value from user object
        $obValue = $obUser->$sKey;
        if (empty($obValue)) {
            return $sDefaultValue;
        }

        return $obValue;
    }

    /**
     * Put value to storage
     * @param string $sKey
     * @param mixed  $obValue
     */
    public function put($sKey, $obValue)
    {
        if (empty($sKey)) {
            return;
        }

        //Get auth user object
        $obUser = UserHelper::instance()->getUser();
        if (empty($obUser)) {
            $this->putDefaultStorageValue($sKey, $obValue);

            return;
        }

        $obUser->$sKey = $obValue;
        $obUser->save();
    }

    /**
     * Clear value in storage
     * @param string $sKey
     */
    public function clear($sKey)
    {
        if (empty($sKey)) {
            return;
        }

        $this->clearDefaultStorageValue($sKey);

        //Get auth user object
        $obUser = UserHelper::instance()->getUser();
        if (empty($obUser)) {
            return;
        }

        $obUser->$sKey = null;
        $obUser->save();
    }

    /**
     * Get list value from storage
     * @param string $sKey
     * @return array
     */
    public function getList($sKey)
    {
        if (empty($sKey)) {
            return [];
        }

        $arDefaultStorageValue = $this->getListDefaultStorageValue($sKey);

        //Get auth user object
        $obUser = UserHelper::instance()->getUser();
        if (empty($obUser)) {
            return $arDefaultStorageValue;
        }

        //Get value from user object
        $arValueList = $obUser->$sKey;
        if (empty($arValueList) || !is_array($arValueList)) {
            $arValueList = [];
        }

        if (!empty($arDefaultStorageValue)) {
            $arValueList = array_merge($arDefaultStorageValue, $arValueList);
            $arValueList = array_unique($arValueList);

            $this->put($sKey, $arValueList);
            $this->clearDefaultStorageValue($sKey);
        }

        return $arValueList;
    }

    /**
     * Get value from storage
     * @param string $sKey
     * @param mixed  $sDefaultValue
     *
     * @return mixed
     */
    protected function getDefaultStorageValue($sKey, $sDefaultValue = null)
    {
        if (empty($this->obDefaultStorage)) {
            return $sDefaultValue;
        }

        return $this->obDefaultStorage->get($sKey, $sDefaultValue);
    }

    /**
     * Get list value from storage
     * @param string $sKey
     *
     * @return array
     */
    protected function getListDefaultStorageValue($sKey)
    {
        if (empty($this->obDefaultStorage)) {
            return [];
        }

        return $this->obDefaultStorage->getList($sKey);
    }

    /**
     * Put value to storage
     * @param string $sKey
     * @param mixed  $obValue
     */
    protected function putDefaultStorageValue($sKey, $obValue)
    {
        if (empty($this->obDefaultStorage)) {
            return;
        }

        $this->obDefaultStorage->put($sKey, $obValue);
    }

    /**
     * Clear value in storage
     * @param string $sKey
     */
    protected function clearDefaultStorageValue($sKey)
    {
        if (empty($this->obDefaultStorage)) {
            return;
        }

        $this->obDefaultStorage->clear($sKey);
    }
}
