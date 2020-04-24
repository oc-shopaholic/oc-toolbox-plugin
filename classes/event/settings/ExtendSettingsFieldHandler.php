<?php namespace Lovata\Toolbox\Classes\Event\Settings;

use Lovata\Toolbox\Classes\Event\AbstractBackendFieldHandler;
use Lovata\Toolbox\Classes\Helper\PageHelper;
use Lovata\ToolBox\Models\Settings;
use System\Classes\PluginManager;

/**
 * Class ExtendSettingsFieldHandler
 * @package Lovata\Toolbox\Classes\Event\Settings
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ExtendSettingsFieldHandler extends AbstractBackendFieldHandler
{
    /**
     * Extend form fields
     * @param \Backend\Widgets\Form $obWidget
     */
    protected function extendFields($obWidget)
    {
        $arTabFields = $this->getAvailablePageList();



        $obWidget->addTabFields($arTabFields);
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass() : string
    {
        return Settings::class;
    }

    /**
     * Get controller class name
     * @return string
     */
    protected function getControllerClass() : string
    {
        return \System\Controllers\Settings::class;
    }

    /**
     * Get available page list.
     * @return array
     */
    protected function getAvailablePageList() : array
    {
        $arTabFields = [];

        $arProductPage = PageHelper::instance()->getPageNameList();

        // Shopaholic plugin.
        if (PluginManager::instance()->hasPlugin('Lovata.Shopaholic')) {
            $arTabFields['product_page_id'] = [
                'tab'         => 'lovata.toolbox::lang.tab.page_settings',
                'label'       => 'lovata.toolbox::lang.field.set_page_for_product',
                'span'        => 'left',
                'type'        => 'dropdown',
                'emptyOption' => 'lovata.toolbox::lang.field.empty',
                'options'     => $arProductPage,
            ];
            $arTabFields['category_page_id'] = [
                'tab'         => 'lovata.toolbox::lang.tab.page_settings',
                'label'       => 'lovata.toolbox::lang.field.set_page_for_category',
                'span'        => 'left',
                'type'        => 'dropdown',
                'emptyOption' => 'lovata.toolbox::lang.field.empty',
                'options'     => $arProductPage,
            ];
            $arTabFields['brand_page_id'] = [
                'tab'         => 'lovata.toolbox::lang.tab.page_settings',
                'label'       => 'lovata.toolbox::lang.field.set_page_for_brand',
                'span'        => 'left',
                'type'        => 'dropdown',
                'emptyOption' => 'lovata.toolbox::lang.field.empty',
                'options'     => $arProductPage,
            ];
            $arTabFields['promo_block_page_id'] = [
                'tab'         => 'lovata.toolbox::lang.tab.page_settings',
                'label'       => 'lovata.toolbox::lang.field.set_page_for_promo_block',
                'span'        => 'left',
                'type'        => 'dropdown',
                'emptyOption' => 'lovata.toolbox::lang.field.empty',
                'options'     => $arProductPage,
            ];
        }

        return $arTabFields;
    }
}
