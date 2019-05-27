<?php namespace Lovata\Toolbox\Models;

use October\Rain\Database\Model;

/**
 * Class CommonSettings
 * @package Lovata\Toolbox\Models
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CommonSettings extends Model
{
    const SETTINGS_CODE = '';

    public $implement = [
        'System.Behaviors.SettingsModel',
        '@RainLab.Translate.Behaviors.TranslatableModel',
    ];

    public $translatable = [];
    public $settingsCode = '';
    public $settingsFields = 'fields.yaml';

    public $attachOne = [];
    public $attachMany = [];

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

        //Get settings object
        $obSettings = static::where('item', static::SETTINGS_CODE)->first();
        if (empty($obSettings)) {
            return static::get($sCode, $sDefaultValue);
        }

        $sValue = $obSettings->$sCode;
        if ($sValue === null) {
            return $sDefaultValue;
        }

        return $sValue;
    }
}
