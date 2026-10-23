<?php namespace Lovata\Toolbox\Models;

use Lang;
use Model;
use October\Rain\Database\Traits\Validation;
use October\Rain\Exception\ApplicationException;
use October\Rain\Exception\ValidationException;

use Kharanenka\Scope\ActiveField;

/**
 * Class ImageProfile
 * @package Lovata\Toolbox\Models
 * @author  Pavel Lautsevich, p.lautsevich@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 *
 * @property int                                                     $id
 * @property bool                                                    $active
 * @property string                                                  $name
 * @property int                                                     $width
 * @property string                                                  $media
 * @property \October\Rain\Argon\Argon                               $created_at
 * @property \October\Rain\Argon\Argon                               $updated_at
 *
 * @property \October\Rain\Database\Collection|ImageSet[]            $image_sets
 * @method static \October\Rain\Database\Relations\HasMany|ImageSet image_sets()
 */
class ImageProfile extends Model
{
    use Validation;
    use ActiveField;

    const APPROACH_MOBILE_FIRST = 'mobile_first';
    const APPROACH_DESKTOP_FIRST = 'desktop_first';

    public $table = 'lovata_toolbox_image_profiles';

    public $rules = [
        'name'  => 'required',
        'width' => 'required|integer|min:1|unique:lovata_toolbox_image_profiles',
    ];

    public $attributeNames = [
        'name'  => 'lovata.toolbox::lang.field.name',
        'width' => 'lovata.toolbox::lang.field.screen_width',
    ];

    public $customMessages = [
        'width.unique' => 'lovata.toolbox::lang.message.e_image_profile_width_exists',
    ];

    public $hasMany = [
        'image_sets' => [ImageSet::class, 'key' => 'profile_id'],
    ];

    public $fillable = [
        'active',
        'name',
        'width',
    ];

    public $dates = ['created_at', 'updated_at'];

    /**
     * Get approach from settings: "mobile_first" or "desktop_first" (default)
     * @return string
     */
    public static function getApproach()
    {
        return self::normalizeApproach(Settings::getValue('image_profiles_approach'));
    }

    /**
     * Get valid approach value, "desktop_first" is used by default
     * @param string|null $sApproach
     * @return string
     */
    public static function normalizeApproach($sApproach)
    {
        return $sApproach == self::APPROACH_MOBILE_FIRST ? self::APPROACH_MOBILE_FIRST : self::APPROACH_DESKTOP_FIRST;
    }

    /**
     * Get media condition, using the Media Queries Level 4 range syntax
     * @return string
     */
    public function getMediaAttribute()
    {
        $iWidth = (int) $this->width;
        if (self::getApproach() == self::APPROACH_MOBILE_FIRST) {
            return '(width >= '.$iWidth.'px)';
        }

        return '(width < '.$iWidth.'px)';
    }

    /**
     * Before validate event handler: name is unique case-insensitively among all profiles
     * @throws ValidationException
     */
    public function beforeValidate()
    {
        $sName = mb_strtolower(trim((string) $this->name));
        if ($sName === '') {
            return;
        }

        $obQuery = self::query();
        if ($this->exists) {
            $obQuery->where('id', '<>', $this->id);
        }

        foreach ($obQuery->pluck('name') as $sProfileName) {
            if (mb_strtolower(trim((string) $sProfileName)) !== $sName) {
                continue;
            }

            $sMessage = Lang::get('lovata.toolbox::lang.message.e_image_profile_name_exists', ['name' => $this->name]);

            throw new ValidationException(['name' => $sMessage]);
        }
    }

    /**
     * Before delete event handler: profile with image sets can not be deleted
     * @throws ApplicationException
     */
    public function beforeDelete()
    {
        if (!$this->image_sets()->exists()) {
            return;
        }

        $sMessage = Lang::get('lovata.toolbox::lang.message.e_image_profile_has_sets', ['name' => $this->name]);

        throw new ApplicationException($sMessage);
    }

    /**
     * After save event handler
     */
    public function afterSave()
    {
        ImageSet::clearAllItemCache();
    }

    /**
     * After delete event handler
     */
    public function afterDelete()
    {
        ImageSet::clearAllItemCache();
    }
}
