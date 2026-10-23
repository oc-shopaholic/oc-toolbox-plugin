<?php namespace Lovata\Toolbox\Models;

/**
 * Class Settings
 * @package Lovata\Toolbox\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Settings extends CommonSettings
{
    const SETTINGS_CODE = 'lovata_toolbox_settings';

    public $settingsCode = 'lovata_toolbox_settings';

    /**
     * After save event handler.
     * Media condition and order of image sets are cached, so Item cache is cleared when approach is changed.
     */
    public function afterSave()
    {
        $arOriginalValue = json_decode((string) $this->getRawOriginal('value'), true);
        $sOriginalApproach = ImageProfile::normalizeApproach(array_get((array) $arOriginalValue, 'image_profiles_approach'));
        $sApproach = ImageProfile::normalizeApproach(array_get((array) $this->value, 'image_profiles_approach'));
        if ($sOriginalApproach == $sApproach) {
            return;
        }

        ImageSet::clearAllItemCache();
    }
}
