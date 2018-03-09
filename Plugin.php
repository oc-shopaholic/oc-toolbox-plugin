<?php namespace Lovata\Toolbox;

use System\Classes\PluginBase;
use Lovata\Toolbox\Components\Pagination;

/**
 * Class Plugin
 * @package Lovata\Toolbox
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Plugin extends PluginBase
{
    const NAME = 'toolbox';
    const CACHE_TAG = 'toolbox';

    /**
     * @return array
     */
    public function registerComponents()
    {
        return [
            Pagination::class => 'Pagination',
        ];
    }

    /**
     * @return array
     */
    public function registerSettings()
    {
        return [
            'config' => [
                'label'       => 'lovata.toolbox::lang.field.site_settings',
                'icon'        => 'icon-cogs',
                'description' => 'lovata.toolbox::lang.field.site_settings_description',
                'class'       => 'Lovata\Toolbox\Models\Settings',
                'order'       => 300,
                'permissions' => [
                    'toolbox-menu-settings',
                ],
            ],
        ];
    }

    /**
     * Plugin boot method
     */
    public function boot()
    {
        if (env('APP_ENV') == 'testing') {
            $this->app->bind(\Lovata\Toolbox\Classes\Item\TestItem::class, \Lovata\Toolbox\Classes\Item\TestItem::class);
            $this->app->bind(\Lovata\Toolbox\Classes\Collection\TestCollection::class, \Lovata\Toolbox\Classes\Collection\TestCollection::class);
        }
    }
}
