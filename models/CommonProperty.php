<?php namespace Lovata\Toolbox\Models;

use Lang;
use Model;
use October\Rain\Database\Traits\Validation;
use October\Rain\Database\Traits\Sortable;

use Kharanenka\Scope\SlugField;
use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\NameField;

/**
 * Class CommonProperty
 * @package Lovata\Toolbox\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 *
 * @property $id
 * @property bool $active
 * @property string $name
 * @property string $code
 * @property string $slug
 * @property string $type (input, textarea, select, checkbox)
 * @property array $settings
 * @property string $description
 * @property int $sort_order
 *
 * @property \October\Rain\Argon\Argon $created_at
 * @property \October\Rain\Argon\Argon $updated_at
 */
class CommonProperty extends Model
{
    const NAME = 'property';

    use Validation;
    use Sortable;
    use ActiveField;
    use NameField;
    use CodeField;
    use SlugField;

    const TYPE_INPUT = 'input';
    const TYPE_TEXT_AREA = 'textarea';
    const TYPE_SELECT = 'select';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_DATE = 'date';
    const TYPE_COLOR_PICKER = 'colorpicker';
    const TYPE_MEDIA_FINDER = 'mediafinder';

    public $table = null;

    public $implement = [
        '@RainLab.Translate.Behaviors.TranslatableModel',
    ];

    public $translatable = ['name', 'description'];

    public $rules = [];

    public $dates = ['created_at', 'updated_at'];
    public $jsonable = ['settings'];

    /**
     * Get widget data
     * @return array
     */
    public function getWidgetData()
    {
        $arResult = [];

        switch ($this->type) {
            /** INPUT TYPE */
            case self::TYPE_INPUT:
                $arResult = [
                    'type' => 'text',
                ];

                if ($this->isTranslatable()) {
                    $arResult['type'] = 'mltext';
                }
                break;
            /** TEXT AREA TYPE */
            case self::TYPE_TEXT_AREA:
                $arResult = [
                    'type' => 'textarea',
                    'size' => 'large',
                ];

                if ($this->isTranslatable()) {
                    $arResult['type'] = 'mltextarea';
                }
                break;
            /** SELECT TYPE */
            case self::TYPE_SELECT:
                //Get property variants
                $arValueList = $this->getPropertyVariants();
                if (empty($arValueList)) {
                    break;
                }

                $arResult = [
                    'type'        => 'dropdown',
                    'emptyOption' => 'lovata.toolbox::lang.field.empty',
                    'options'     => $arValueList,
                ];
                break;
            /** CHECKBOX TYPE */
            case self::TYPE_CHECKBOX:
                //Get property variants
                $arValueList = $this->getPropertyVariants();
                if (empty($arValueList)) {
                    break;
                }

                $arResult = [
                    'type' => 'checkboxlist',
                    'options' => $arValueList,
                ];
                break;
            /** DATE AND TIME PICKER TYPE */
            case self::TYPE_DATE:
                $sMode = $this->getSettingValue('datepicker');
                if (!in_array($sMode, ['date', 'time', 'datetime'])) {
                    break;
                }

                $arResult = [
                    'type' => 'datepicker',
                    'mode' => $sMode,
                ];
                break;
            /** COLOR PICKER TYPE */
            case self::TYPE_COLOR_PICKER:
                $arResult = [
                    'type' => self::TYPE_COLOR_PICKER,
                ];
                break;
            /** FILE FINDER TYPE */
            case self::TYPE_MEDIA_FINDER:
                $sMode = $this->getSettingValue(self::TYPE_MEDIA_FINDER);
                if (!in_array($sMode, ['file', 'image'])) {
                    break;
                }

                $arResult = [
                    'type' => self::TYPE_MEDIA_FINDER,
                    'mode' => $sMode,
                ];
                break;
            default:
                return $arResult;
        }

        //Get common widget settings
        if (empty($arResult)) {
            return $arResult;
        }

        //Get property tab
        $sTabName = $this->getSettingValue('tab');
        if (!empty($sTabName)) {
            $arResult['tab'] = $sTabName;
        } else {
            $arResult['tab'] = 'lovata.toolbox::lang.tab.properties';
        }

        $arResult['span'] = 'left';

        //Get property name with measure
        $arResult['label'] = $this->name;

        return $arResult;
    }

    /**
     * Get property variants from settings
     * @return array
     */
    public function getPropertyVariants()
    {
        $arValueList = [];

        //Get and check settings array
        $arSettings = $this->settings;
        if (empty($arSettings) || !isset($arSettings['list']) || empty($arSettings['list'])) {
            return $arValueList;
        }

        //Get property value variants
        foreach ($arSettings['list'] as $arValue) {
            if (!isset($arValue['value']) || empty($arValue['value'])) {
                continue;
            }

            $arValueList[$arValue['value']] = $arValue['value'];
        }

        return $arValueList;
    }

    /**
     * Check, property is translatable flag
     * @return bool
     */
    public function isTranslatable()
    {
        return (bool) $this->getSettingValue('is_translatable');
    }

    /**
     * Get type list
     * @return array
     */
    public function getTypeOptions()
    {
        $sLangPath = 'lovata.toolbox::lang.type.';

        return [
            self::TYPE_INPUT        => Lang::get($sLangPath.self::TYPE_INPUT),
            self::TYPE_TEXT_AREA    => Lang::get($sLangPath.self::TYPE_TEXT_AREA),
            self::TYPE_CHECKBOX     => Lang::get($sLangPath.self::TYPE_CHECKBOX),
            self::TYPE_SELECT       => Lang::get($sLangPath.self::TYPE_SELECT),
            self::TYPE_DATE         => Lang::get($sLangPath.self::TYPE_DATE),
            self::TYPE_COLOR_PICKER => Lang::get($sLangPath.self::TYPE_COLOR_PICKER),
            self::TYPE_MEDIA_FINDER => Lang::get($sLangPath.self::TYPE_MEDIA_FINDER),
        ];
    }

    /**
     * Get property settings value
     * @param string $sKey
     * @return mixed|null
     */
    protected function getSettingValue($sKey)
    {
        $arSettings = $this->settings;
        if (empty($sKey) || empty($arSettings) || !isset($arSettings[$sKey])) {
            return null;
        }

        return $arSettings[$sKey];
    }
}
