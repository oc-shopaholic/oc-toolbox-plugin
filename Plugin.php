<?php namespace Lovata\Toolbox;

use System\Classes\PluginBase;
use Lovata\Toolbox\Classes\Item\TestItem;
use Lovata\Toolbox\Classes\Collection\TestCollection;

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
            'Lovata\Toolbox\Components\Pagination'  => 'Pagination',
        ];
    }

    /**
     * Plugin boot method
     */
    public function boot()
    {
        if(env('APP_ENV') == 'testing') {
            $this->app->bind(TestItem::class, TestItem::class);
            $this->app->bind(TestCollection::class, TestCollection::class);
        }
    }
}
