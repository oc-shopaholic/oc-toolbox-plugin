<?php namespace Lovata\Toolbox;

use Lang;
use System\Classes\PluginBase;
use Lovata\Toolbox\Components\Pagination;

use Lovata\Toolbox\Classes\Console\ToolBoxHelper;
use Lovata\Toolbox\Classes\Console\CreateAll;
use Lovata\Toolbox\Classes\Console\CreatePlugin;
use Lovata\Toolbox\Classes\Console\CreateModel;
use Lovata\Toolbox\Classes\Console\CreateModelColumn;
use Lovata\Toolbox\Classes\Console\CreateModelField;
use Lovata\Toolbox\Classes\Console\CreateController;
use Lovata\Toolbox\Classes\Console\CreateMigration;
use Lovata\Toolbox\Classes\Console\CreateComponentData;
use Lovata\Toolbox\Classes\Console\CreateComponentList;
use Lovata\Toolbox\Classes\Console\CreateComponentPage;
use Lovata\Toolbox\Classes\Console\CreateItem;
use Lovata\Toolbox\Classes\Console\CreateCollection;
use Lovata\Toolbox\Classes\Console\CreateEventModel;
use Lovata\Toolbox\Classes\Console\CreateStore;
use Lovata\Toolbox\Classes\Console\CreateExtendBackendMenuHandler;
use Lovata\Toolbox\Classes\Console\CreateExtendModelFieldsHandler;
use Lovata\Toolbox\Classes\Console\CreateExtendModelColumnsHandler;

/**
 * Class Plugin
 * @package Lovata\Toolbox
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Plugin extends PluginBase
{
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
     * Extending twig
     * @return array
     */
    public function registerMarkupTags()
    {
        return [
            'functions' => [
                'choice' => function ($sLangString, $iNumber) {
                    return $this->twigChoice($sLangString, $iNumber);
                },
            ],
            'filters' => [
                'phone' => [$this, 'applyPhoneFilter'],
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

    /**
     * Register commands
     */
    public function register()
    {
        $this->registerConsoleCommand('toolbox:helper', ToolBoxHelper::class);
        $this->registerConsoleCommand('toolbox:create.all', CreateAll::class);
        $this->registerConsoleCommand('toolbox:create.plugin', CreatePlugin::class);
        $this->registerConsoleCommand('toolbox:create.model', CreateModel::class);
        $this->registerConsoleCommand('toolbox:create.model.columns', CreateModelColumn::class);
        $this->registerConsoleCommand('toolbox:create.model.fields', CreateModelField::class);
        $this->registerConsoleCommand('toolbox:create.controller', CreateController::class);
        $this->registerConsoleCommand('toolbox:create.migration', CreateMigration::class);
        $this->registerConsoleCommand('toolbox:create.component.data', CreateComponentData::class);
        $this->registerConsoleCommand('toolbox:create.component.list', CreateComponentList::class);
        $this->registerConsoleCommand('toolbox:create.component.page', CreateComponentPage::class);
        $this->registerConsoleCommand('toolbox:create.item', CreateItem::class);
        $this->registerConsoleCommand('toolbox:create.collection', CreateCollection::class);
        $this->registerConsoleCommand('toolbox:create.event.model', CreateEventModel::class);
        $this->registerConsoleCommand('toolbox:create.store', CreateStore::class);
        $this->registerConsoleCommand('toolbox:create.event.menu', CreateExtendBackendMenuHandler::class);
        $this->registerConsoleCommand('toolbox:create.event.fields', CreateExtendModelFieldsHandler::class);
        $this->registerConsoleCommand('toolbox:create.event.columns', CreateExtendModelColumnsHandler::class);
    }

    /**
     * Apply Lang::choice method to string
     * @param string $sLangString
     * @param int $iNumber
     * @return string
     */
    protected function twigChoice($sLangString, $iNumber)
    {
        return Lang::choice($sLangString, $iNumber);
    }

    /**
     * Deletes all characters from string except digits and plus
     * @param $sValue
     * @return string
     */
    public function applyPhoneFilter($sValue) {
        return preg_replace("%[^\d\+]%", '', $sValue);
    }
}
