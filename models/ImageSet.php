<?php namespace Lovata\Toolbox\Models;

use Lang;
use Model;
use System\Models\File;
use October\Rain\Database\Traits\Validation;
use October\Rain\Exception\ValidationException;

use Lovata\Toolbox\Classes\Item\ItemRegistry;

/**
 * Class ImageSet
 * @package Lovata\Toolbox\Models
 * @author  Pavel Lautsevich, p.lautsevich@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 *
 * @property int                                                $id
 * @property int                                                $profile_id
 * @property string                                             $imageable_type
 * @property int                                                $imageable_id
 * @property \October\Rain\Argon\Argon                          $created_at
 * @property \October\Rain\Argon\Argon                          $updated_at
 *
 * @property ImageProfile                                       $profile
 * @method static \October\Rain\Database\Relations\BelongsTo|ImageProfile profile()
 * @property \Model                                             $imageable
 * @property \October\Rain\Database\Collection|File[]           $images
 * @method static \October\Rain\Database\Relations\AttachMany|File images()
 */
class ImageSet extends Model
{
    use Validation;

    public $table = 'lovata_toolbox_image_sets';

    public $rules = [
        'profile_id' => 'required',
    ];

    public $attributeNames = [
        'profile_id' => 'lovata.toolbox::lang.field.image_profile',
    ];

    public $belongsTo = [
        'profile' => [ImageProfile::class],
    ];

    public $morphTo = [
        'imageable' => [],
    ];

    public $attachMany = [
        'images' => [File::class, 'delete' => true],
    ];

    public $fillable = [
        'profile_id',
    ];

    public $dates = ['created_at', 'updated_at'];

    /**
     * Clear Item cache of all elements that have image sets
     */
    public static function clearAllItemCache()
    {
        $obElementList = self::query()
            ->whereNotNull('imageable_type')
            ->whereNotNull('imageable_id')
            ->select('imageable_type', 'imageable_id')
            ->distinct()
            ->get();

        foreach ($obElementList as $obElement) {
            ItemRegistry::clearCache($obElement->imageable_type, $obElement->imageable_id);
        }
    }

    /**
     * Get profile options: active profiles and current profile of set, if it is not active
     * @return array
     */
    public function getProfileOptions()
    {
        $iProfileID = $this->profile_id;

        return ImageProfile::query()
            ->where(function ($obQuery) use ($iProfileID) {
                /** @var \October\Rain\Database\Builder $obQuery */
                $obQuery->where('active', true);
                if (!empty($iProfileID)) {
                    $obQuery->orWhere('id', $iProfileID);
                }
            })
            ->orderBy('width')
            ->pluck('name', 'id')
            ->all();
    }

    /**
     * Add comment to profile field, if profile is not active
     * @param \stdClass $obFieldList
     * @param string    $sContext
     */
    public function filterFields($obFieldList, $sContext = null)
    {
        if (empty($obFieldList->profile) || empty($this->profile_id)) {
            return;
        }

        $obProfile = $this->profile;
        if (empty($obProfile) || $obProfile->active) {
            return;
        }

        $obFieldList->profile->comment = Lang::get('lovata.toolbox::lang.image_profile.profile_disabled');
    }

    /**
     * Before validate event handler: one set per profile per element.
     * Check is skipped for draft without element: repeater binds set to element with deferred binding.
     * @throws ValidationException
     */
    public function beforeValidate()
    {
        if (empty($this->profile_id) || empty($this->imageable_type) || empty($this->imageable_id)) {
            return;
        }

        $obQuery = self::query()
            ->where('profile_id', $this->profile_id)
            ->where('imageable_type', $this->imageable_type)
            ->where('imageable_id', $this->imageable_id);
        if ($this->exists) {
            $obQuery->where('id', '<>', $this->id);
        }

        if (!$obQuery->exists()) {
            return;
        }

        $obProfile = ImageProfile::find($this->profile_id);
        $sMessage = Lang::get('lovata.toolbox::lang.message.e_image_set_profile_exists', [
            'name' => !empty($obProfile) ? $obProfile->name : $this->profile_id,
        ]);

        throw new ValidationException(['profile' => $sMessage]);
    }

    /**
     * After save event handler
     */
    public function afterSave()
    {
        $this->clearItemCache();
    }

    /**
     * After delete event handler
     */
    public function afterDelete()
    {
        $this->clearItemCache();
    }

    /**
     * Clear Item cache of element. Draft without element is skipped.
     */
    protected function clearItemCache()
    {
        ItemRegistry::clearCache($this->imageable_type, $this->imageable_id);

        //Set was moved to another element
        $sOriginalType = $this->getOriginal('imageable_type');
        $iOriginalID = $this->getOriginal('imageable_id');
        if ($sOriginalType != $this->imageable_type || $iOriginalID != $this->imageable_id) {
            ItemRegistry::clearCache($sOriginalType, $iOriginalID);
        }
    }
}
