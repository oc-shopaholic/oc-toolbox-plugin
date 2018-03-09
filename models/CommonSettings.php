<?php namespace Lovata\Toolbox\Models;

use October\Rain\Database\Model;

/**
 * Class CommonSettings
 * @package Lovata\Toolbox\Models
 * @author  Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
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
            return null;
        }

        //Get settings object
        $obSettings = self::where('item', static::SETTINGS_CODE)->first();
        if (empty($obSettings)) {
            return null;
        }

        $sValue = $obSettings->$sCode;
        if (empty($sValue)) {
            return $sDefaultValue;
        }

        return $sValue;
    }
}