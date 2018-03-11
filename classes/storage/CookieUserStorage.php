<?php namespace Lovata\Toolbox\Classes\Storage;

use Cookie;

/**
 * Class CookieUserStorage
 * @package Lovata\Toolbox\Classes\Storage
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CookieUserStorage extends AbstractUserStorage
{
    protected $iMinutePeriod = 1440;

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

        $obValue = Cookie::get($sKey);
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

        Cookie::queue($sKey, $obValue, $this->iMinutePeriod);
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

        Cookie::forget($sKey);
    }

    /**
     * Set minute period
     * @param int $iPeriod
     */
    public function setMinutePeriod($iPeriod)
    {
        $this->iMinutePeriod = (int) $iPeriod;
    }
}
