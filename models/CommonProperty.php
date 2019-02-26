<?php namespace Lovata\Toolbox\Models;

use Lang;
use Backend\Models\ImportModel;
use October\Rain\Database\Traits\Validation;
use October\Rain\Database\Traits\Sortable;

use Kharanenka\Scope\TypeField;
use Kharanenka\Scope\SlugField;
use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\NameField;

/**
 * Class CommonProperty
 * @package Lovata\Toolbox\Models
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 *
 * @property                           $id
 * @property bool                      $active
 * @property string                    $name
 * @property string                    $code
 * @property string                    $slug
 * @property string                    $type (input, textarea, select, checkbox)
 * @property array                     $settings
 * @property string                    $description
 * @property int                       $sort_order
 *
 * @property \October\Rain\Argon\Argon $created_at
 * @property \October\Rain\Argon\Argon $updated_at
 */
class CommonProperty extends ImportModel
{
    const NAME = 'property';

    use Validation;
    use Sortable;
    use ActiveField;
    use NameField;
    use CodeField;
    use SlugField;
    use TypeField;

    const TYPE_INPUT = 'input';
    const TYPE_NUMBER = 'number';
    const TYPE_TEXT_AREA = 'textarea';
    const TYPE_RICH_EDITOR = 'rich_editor';
    const TYPE_SINGLE_CHECKBOX = 'single_checkbox';
    const TYPE_SWITCH = 'switch';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_BALLOON = 'balloon_selector';
    const TYPE_TAG_LIST = 'tag_list';
    const TYPE_SELECT = 'select';
    const TYPE_RADIO = 'radio';
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

    public $attachOne = [
        'import_file' => [\System\Models\File::class, 'public' => false],
    ];

    /**
     * Get widget data
     * @return array
     */
    public function getWidgetData()
    {
        $arResult = [];

        switch ($this->type) {
            case self::TYPE_INPUT:
                $arResult = $this->getInputFieldSettings();
                break;
            case self::TYPE_NUMBER:
                $arResult = $this->getNumberFieldSettings();
                break;
            case self::TYPE_TEXT_AREA:
                $arResult = $this->getTextareaFieldSettings();
                break;
            case self::TYPE_RICH_EDITOR:
                $arResult = $this->getRichEditorFieldSettings();
                break;
            case self::TYPE_SINGLE_CHECKBOX:
                $arResult = $this->getSingleCheckboxFieldSettings();
                break;
            case self::TYPE_SWITCH:
                $arResult = $this->getSwitchFieldSettings();
                break;
            case self::TYPE_CHECKBOX:
                $arResult = $this->getCheckboxListSettings();
                break;
            case self::TYPE_BALLOON:
                $arResult = $this->getBalloonSettings();
                break;
            case self::TYPE_TAG_LIST:
                $arResult = $this->getTagListSettings();
                break;
            case self::TYPE_SELECT:
                $arResult = $this->getSelectSettings();
                break;
            case self::TYPE_RADIO:
                $arResult = $this->getRadioSettings();
                break;
            case self::TYPE_DATE:
                $arResult = $this->getDateSettings();
                break;
            case self::TYPE_COLOR_PICKER:
                $arResult = $this->getColorPickerSettings();
                break;
            /** FILE FINDER TYPE */
            case self::TYPE_MEDIA_FINDER:
                $arResult = $this->getMediaFinderSettings();
                break;
            default:
                return $arResult;
        }

        //Get common widget settings
        if (empty($arResult)) {
            return $arResult;
        }

        $arResult = array_merge($arResult, $this->getDefaultConfigSettings());

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

        natsort($arValueList);

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
            self::TYPE_INPUT           => Lang::get($sLangPath.self::TYPE_INPUT),
            self::TYPE_NUMBER          => Lang::get($sLangPath.self::TYPE_NUMBER),
            self::TYPE_TEXT_AREA       => Lang::get($sLangPath.self::TYPE_TEXT_AREA),
            self::TYPE_RICH_EDITOR     => Lang::get($sLangPath.self::TYPE_RICH_EDITOR),
            self::TYPE_SINGLE_CHECKBOX => Lang::get($sLangPath.self::TYPE_SINGLE_CHECKBOX),
            self::TYPE_SWITCH          => Lang::get($sLangPath.self::TYPE_SWITCH),
            self::TYPE_CHECKBOX        => Lang::get($sLangPath.self::TYPE_CHECKBOX),
            self::TYPE_TAG_LIST        => Lang::get($sLangPath.self::TYPE_TAG_LIST),
            self::TYPE_SELECT          => Lang::get($sLangPath.self::TYPE_SELECT),
            self::TYPE_RADIO           => Lang::get($sLangPath.self::TYPE_RADIO),
            self::TYPE_BALLOON         => Lang::get($sLangPath.self::TYPE_BALLOON),
            self::TYPE_DATE            => Lang::get($sLangPath.self::TYPE_DATE),
            self::TYPE_COLOR_PICKER    => Lang::get($sLangPath.self::TYPE_COLOR_PICKER),
            self::TYPE_MEDIA_FINDER    => Lang::get($sLangPath.self::TYPE_MEDIA_FINDER),
        ];
    }

    /**
     * Import item list from CSV file
     * @param array $arElementList
     * @param null  $sSessionKey
     * @throws \Throwable
     */
    public function importData($arElementList, $sSessionKey = null)
    {
    }

    /**
     * Get field setting with type "text"
     * @return array
     */
    protected function getInputFieldSettings() : array
    {
        $arResult = [
            'type' => 'text',
        ];

        if ($this->isTranslatable()) {
            $arResult['type'] = 'mltext';
        }

        return $arResult;
    }

    /**
     * Get field setting with type "number"
     * @return array
     */
    protected function getNumberFieldSettings() : array
    {
        $arResult = [
            'type' => 'number',
        ];

        return $arResult;
    }

    /**
     * Get field setting with type "textarea"
     * @return array
     */
    protected function getTextareaFieldSettings() : array
    {
        $arResult = [
            'type' => 'textarea',
            'size' => 'large',
        ];

        if ($this->isTranslatable()) {
            $arResult['type'] = 'mltextarea';
        }

        return $arResult;
    }

    /**
     * Get field setting with type "rich editor"
     * @return array
     */
    protected function getRichEditorFieldSettings() : array
    {
        $arResult = [
            'type' => 'richeditor',
            'size' => 'large',
        ];

        if ($this->isTranslatable()) {
            $arResult['type'] = 'mlricheditor';
        }

        return $arResult;
    }

    /**
     * Get field setting with type "checkbox"
     * @return array
     */
    protected function getSingleCheckboxFieldSettings() : array
    {
        $arResult = [
            'type' => 'checkbox',
        ];

        return $arResult;
    }

    /**
     * Get field setting with type "switch"
     * @return array
     */
    protected function getSwitchFieldSettings() : array
    {
        $arResult = [
            'type' => 'switch',
        ];

        return $arResult;
    }

    /**
     * Get field setting with type "checkbox list"
     * @return array
     */
    protected function getCheckboxListSettings() : array
    {
        //Get property variants
        $arValueList = $this->getPropertyVariants();
        if (empty($arValueList)) {
            return [];
        }

        $arResult = [
            'type'    => 'checkboxlist',
            'options' => $arValueList,
        ];

        return $arResult;
    }

    /**
     * Get field setting with type "balloon-selector"
     * @return array
     */
    protected function getBalloonSettings() : array
    {
        //Get property variants
        $arValueList = $this->getPropertyVariants();
        if (empty($arValueList)) {
            return [];
        }

        $arResult = [
            'type'    => 'balloon-selector',
            'options' => $arValueList,
        ];

        return $arResult;
    }

    /**
     * Get field setting with type "tag list"
     * @return array
     */
    protected function getTagListSettings() : array
    {
        //Get property variants
        $arValueList = $this->getPropertyVariants();
        if (empty($arValueList)) {
            return [];
        }

        $arResult = [
            'type'    => 'taglist',
            'options' => $arValueList,
        ];

        return $arResult;
    }

    /**
     * Get field setting with type "select"
     * @return array
     */
    protected function getSelectSettings() : array
    {
        //Get property variants
        $arValueList = $this->getPropertyVariants();
        if (empty($arValueList)) {
            return [];
        }

        $arResult = [
            'type'        => 'dropdown',
            'emptyOption' => 'lovata.toolbox::lang.field.empty',
            'options'     => $arValueList,
        ];

        return $arResult;
    }

    /**
     * Get field setting with type "radio"
     * @return array
     */
    protected function getRadioSettings() : array
    {
        //Get property variants
        $arValueList = $this->getPropertyVariants();
        if (empty($arValueList)) {
            return [];
        }

        $arResult = [
            'type'    => 'radio',
            'options' => $arValueList,
        ];

        return $arResult;
    }

    /**
     * Get field setting with type "date"
     * @return array
     */
    protected function getDateSettings() : array
    {
        $sMode = $this->getSettingValue('datepicker');
        if (!in_array($sMode, ['date', 'time', 'datetime'])) {
            return [];
        }

        $arResult = [
            'type' => 'datepicker',
            'mode' => $sMode,
        ];

        return $arResult;
    }

    /**
     * Get field setting with type "color picker"
     * @return array
     */
    protected function getColorPickerSettings() : array
    {
        $arResult = [
            'type' => self::TYPE_COLOR_PICKER,
        ];

        return $arResult;
    }

    /**
     * Get field setting with type "media finder"
     * @return array
     */
    protected function getMediaFinderSettings() : array
    {
        $sMode = $this->getSettingValue(self::TYPE_MEDIA_FINDER);
        if (!in_array($sMode, ['file', 'image'])) {
            return [];
        }

        $arResult = [
            'type' => self::TYPE_MEDIA_FINDER,
            'mode' => $sMode,
        ];

        return $arResult;
    }

    /**
     * Get default config field settings
     * @return array
     */
    protected function getDefaultConfigSettings() : array
    {
        $arResult = [
            'tab'   => 'lovata.toolbox::lang.tab.properties',
            'span'  => 'left',
            'label' => $this->name,
            'comment' => $this->description,
        ];

        //Get property tab
        $sTabName = $this->getSettingValue('tab');
        if (!empty($sTabName)) {
            $arResult['tab'] = $sTabName;
        }

        return $arResult;
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
