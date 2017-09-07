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
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
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
        
        switch($this->type) {
            /** INPUT TYPE */
            case self::TYPE_INPUT :
                $arResult = [
                    'type' => 'text',
                ];
                break;
            /** TEXT AREA TYPE */
            case self::TYPE_TEXT_AREA :
                $arResult = [
                    'type' => 'textarea',
                    'size' => 'large',
                ];
                break;
            /** SELECT TYPE */
            case self::TYPE_SELECT :
                
                //Get property variants
                $arValueList = $this->getPropertyVariants();
                if(empty($arValueList)) {
                    break;
                }
                
                $arResult = [
                    'type'        => 'dropdown',
                    'emptyOption' => 'lovata.toolbox::lang.field.empty',
                    'options'     => $arValueList,
                ];
                break;
            /** CHECKBOX TYPE */
            case self::TYPE_CHECKBOX :

                //Get property variants
                $arValueList = $this->getPropertyVariants();
                if(empty($arValueList)) {
                    break;
                }

                $arResult = [
                    'type' => 'checkboxlist',
                    'options' => $arValueList,
                ];
                break;
            /** DATE AND TIME PICKER TYPE */
            case self::TYPE_DATE :

                $sMode = null;
                $arSettings = $this->settings;
                if(empty($arSettings) || !isset($arSettings['datepicker']) || empty($arSettings['datepicker'])) {
                    break;
                }
                
                $sMode = $arSettings['datepicker'];
                if(!in_array($sMode, ['date', 'time', 'datetime'])) {
                    break;
                }
                
                $arResult = [
                    'type' => 'datepicker',
                    'mode' => $sMode,
                ];
                break;
            /** COLOR PICKER TYPE */
            case self::TYPE_COLOR_PICKER :
                $arResult = [
                    'type' => 'colorpicker',
                ];
                break;
            /** FILE FINDER TYPE */
            case self::TYPE_MEDIA_FINDER :

                $sMode = null;
                $arSettings = $this->settings;
                if(empty($arSettings) || !isset($arSettings['mediafinder']) || empty($arSettings['mediafinder'])) {
                    break;
                }

                $sMode = $arSettings['mediafinder'];
                if(!in_array($sMode, ['file', 'image'])) {
                    break;
                }

                $arResult = [
                    'type' => 'mediafinder',
                    'mode' => $sMode,
                ];
                break;
        }
        
        //Get common widget settings
        if(!empty($arResult)) {
            
            //Get property tab
            $arResult['tab'] = 'lovata.propertiesshopaholic::lang.field.properties';
            $arResult['span'] = 'left';

            //Get property name with measure
            $arResult['label'] = $this->name;
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
        if(empty($sKey) || empty($arSettings) || !isset($arSettings[$sKey])) {
            return null;
        }

        return $arSettings[$sKey];
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
        if(empty($arSettings) || !isset($arSettings['list']) || empty($arSettings['list'])) {
            return $arValueList;
        }

        //Get property value variants
        foreach($arSettings['list'] as $arValue) {

            if(!isset($arValue['value']) || empty($arValue['value'])) {
                continue;
            }

            $arValueList[$arValue['value']] = $arValue['value'];
        }
        
        return $arValueList;
    }

    /**
     * Get type list
     * @return array
     */
    public function getTypeOptions()
    {
        return [
            self::TYPE_INPUT        => Lang::get('lovata.toolbox::lang.type.' . self::TYPE_INPUT),
            self::TYPE_TEXT_AREA    => Lang::get('lovata.toolbox::lang.type.' . self::TYPE_TEXT_AREA),
            self::TYPE_CHECKBOX     => Lang::get('lovata.toolbox::lang.type.' . self::TYPE_CHECKBOX),
            self::TYPE_SELECT       => Lang::get('lovata.toolbox::lang.type.' . self::TYPE_SELECT),
            self::TYPE_DATE         => Lang::get('lovata.toolbox::lang.type.' . self::TYPE_DATE),
            self::TYPE_COLOR_PICKER => Lang::get('lovata.toolbox::lang.type.' . self::TYPE_COLOR_PICKER),
            self::TYPE_MEDIA_FINDER => Lang::get('lovata.toolbox::lang.type.' . self::TYPE_MEDIA_FINDER),
        ];
    }
}