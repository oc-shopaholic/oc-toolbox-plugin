<?php namespace Lovata\Toolbox\Classes\Console;

use Lang;
use Illuminate\Console\Command;
use Lovata\Toolbox\Traits\Console\LogoTrait;

/**
 * Class ToolBoxHelper
 * @package Lovata\Toolbox\Classes\Console
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ToolBoxHelper extends Command
{
    use LogoTrait;

    const HEADER_COMMAND_LIST = 'Command list';
    const HEADER_DESCRIPTION = 'Description';

    /** @var string The console command name. */
    protected $name = 'toolbox:helper';
    /** @var string The console command description. */
    protected $description = 'Command list.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->logoToolBox();

        $arHeaderList = [self::HEADER_COMMAND_LIST, self::HEADER_DESCRIPTION];

        $arRowList = [
            [
                'toolbox:helper',
                Lang::get('lovata.toolbox::lang.message.table_toolbox_helper'),
            ],
            [
                'toolbox:create.all',
                Lang::get('lovata.toolbox::lang.message.table_toolbox_create', ['description' => 'all pack.']),
            ],
            [
                'toolbox:create.plugin',
                Lang::get('lovata.toolbox::lang.message.table_toolbox_create', ['description' => 'plugin.']),
            ],
            [
                'toolbox:create.model',
                Lang::get('lovata.toolbox::lang.message.table_toolbox_create', ['description' => 'model.']),
            ],
            [
                'toolbox:create.model.columns',
                Lang::get('lovata.toolbox::lang.message.table_toolbox_create', ['description' => 'columns model.']),
            ],
            [
                'toolbox:create.model.fields',
                Lang::get('lovata.toolbox::lang.message.table_toolbox_create', ['description' => 'fields model.']),
            ],
            [
                'toolbox:create.controller',
                Lang::get('lovata.toolbox::lang.message.table_toolbox_create', ['description' => 'controller.']),
            ],
            [
                'toolbox:create.migration',
                Lang::get('lovata.toolbox::lang.message.table_toolbox_create', ['description' => 'migration.']),
            ],
            [
                'toolbox:create.component.data',
                Lang::get('lovata.toolbox::lang.message.table_toolbox_create', ['description' => 'component data.']),
            ],
            [
                'toolbox:create.component.list',
                Lang::get('lovata.toolbox::lang.message.table_toolbox_create', ['description' => 'component list.']),
            ],
            [
                'toolbox:create.component.page',
                Lang::get('lovata.toolbox::lang.message.table_toolbox_create', ['description' => 'component page.']),
            ],
            [
                'toolbox:create.item',
                Lang::get('lovata.toolbox::lang.message.table_toolbox_create', ['description' => 'item.']),
            ],
            [
                'toolbox:create.collection',
                Lang::get('lovata.toolbox::lang.message.table_toolbox_create', ['description' => 'collection.']),
            ],
            [
                'toolbox:create.store',
                Lang::get('lovata.toolbox::lang.message.table_toolbox_create', ['description' => 'store.']),
            ],
            [
                'toolbox:create.event.model',
                Lang::get('lovata.toolbox::lang.message.table_toolbox_create', ['description' => 'event model.']),
            ],
            [
                'toolbox:create.event.menu',
                Lang::get('lovata.toolbox::lang.message.table_toolbox_create', ['description' => 'extend backend menu.']),
            ],
            [
                'toolbox:create.event.fields',
                Lang::get('lovata.toolbox::lang.message.table_toolbox_create', ['description' => 'extend model fields.']),
            ],
            [
                'toolbox:create.event.columns',
                Lang::get('lovata.toolbox::lang.message.table_toolbox_create', ['description' => 'extend model columns.']),
            ],
        ];

        $this->table($arHeaderList, $arRowList);
    }
}
