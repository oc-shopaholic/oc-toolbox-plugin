<?php namespace Lovata\Toolbox\Models;

use October\Rain\Database\Traits\Multisite;
use System\Models\SettingModel;

/**
 * Class CommonSettings
 * @package Lovata\Toolbox\Models
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CommonSettings extends SettingModel
{
    use Multisite;

    const SETTINGS_CODE = '';

    public static $arCacheValue = [];

    public $implement = [
        '@RainLab.Translate.Behaviors.TranslatableModel',
    ];

    public $translatable = [];
    public $settingsCode = '';
    public $settingsFields = 'fields.yaml';

    public $attachOne = [];
    public $attachMany = [];

    protected $propagatable = [];

    /**
     * Get setting value
     * @param string $sCode
     * @param string $sDefaultValue
     * @return null|string
     */
    public static function getValue($sCode, $sDefaultValue = null)
    {
        if (empty($sCode)) {
            return $sDefaultValue;
        }

        if (isset(static::$arCacheValue[$sCode])) {
            return static::$arCacheValue[$sCode];
        }

        //Get settings object
        $obSettings = static::where('item', static::SETTINGS_CODE)->first();
        if (empty($obSettings)) {
            static::$arCacheValue[$sCode] = static::get($sCode, $sDefaultValue);

            return static::$arCacheValue[$sCode];
        }

        $sValue = $obSettings->$sCode;
        if ($sValue === null) {
            return $sDefaultValue;
        }

        static::$arCacheValue[$sCode] = $sValue;

        return $sValue;
    }
}
