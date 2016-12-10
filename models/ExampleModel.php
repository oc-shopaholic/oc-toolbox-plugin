<?php namespace Lovata\Toolbox\Models;

use Carbon\Carbon;
use Model;
use Lovata\Toolbox\Plugin;
use October\Rain\Database\Builder;
use Kharanenka\Helper\CCache;

/**
 * Class ExampleModel
 * @package Lovata\Toolbox\Models
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin Builder
 * @mixin \Eloquent
 *
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ExampleModel extends Model
{
    const CACHE_TAG_ELEMENT = 'toolbox-example-element';
    const CACHE_TAG_LIST = 'toolbox-example-list';

    use \October\Rain\Database\Traits\Validation;
    use \Kharanenka\Scope\SlugField;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'CHANGE_ME';
    
    public $rules = [];
    public $customMessages = [];
    public $attributeNames = [];
    protected $dates = ['created_at', 'updated_at'];

    /**
     * ExampleModel constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function afterSave()
    {
        $this->clearCache();
    }

    public function afterDelete()
    {
        $this->clearCache();
    }

    /**
     * Clear cache data
     */
    public function clearCache()
    {
        //Clear cached data
        CCache::clear([Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT], $this->id);
        CCache::clear([Plugin::CACHE_TAG, self::CACHE_TAG_LIST], self::CACHE_TAG_LIST);

        CCache::clear([Plugin::CACHE_TAG, self::CACHE_TAG_LIST]);
    }

    /**
     * Get element data
     * @return array
     */
    public function getData()
    {
        $arResult = [
            'id' => $this->id,
        ];

        return $arResult;
    }

    /**
     * Get cached data
     * @param int $iElementID
     * @param null|ExampleModel $obElement
     * @return array|null
     */
    public static function getCacheData($iElementID, $obElement = null)
    {
        if(empty($iElementID)) {
            return null;
        }

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT];
        $sCacheKey = $iElementID;

        $arResult = CCache::get($arCacheTags, $sCacheKey);
        if(empty($arResult)) {

            //Get element object
            if(empty($obElement)) {
                $obElement = self::find($iElementID);
            }

            if(empty($obElement)) {
                return null;
            }

            $arResult = $obElement->getData();

            //Set cache data
            CCache::forever($arCacheTags, $sCacheKey, $arResult);
        }

        return $arResult;
    }
}