<?php namespace Lovata\Toolbox\Classes\Console;

use Lang;

/**
 * Class CreateAll
 * @package Lovata\Toolbox\Classes\Console
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateAll extends CommonCreateFile
{
    /** @var string The console command name. */
    protected $name = 'toolbox:create.all';
    /** @var string The console command description. */
    protected $description = 'Create all pack.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        $this->setModel();
        $this->setController();
        $this->setFieldList();
        $this->setSorting();
        $this->setImportExportCSV();
        $this->setAdditionList(self::CODE_COMMAND_PARENT);
        $this->callCommandList();
    }

    /**
     * Call command list
     */
    protected function callCommandList()
    {
        $sMessage = Lang::get('lovata.toolbox::lang.message.create', ['name' => self::CODE_MODEL]);

        if ($this->confirm($sMessage, true)) {
            $this->call('toolbox:create.model', ['data' => $this->arData]);
        }

        $sMessage = Lang::get('lovata.toolbox::lang.message.create', ['name' => self::CODE_CONTROLLER]);

        if ($this->confirm($sMessage, true)) {
            $this->call('toolbox:create.controller', ['data' => $this->arData]);
        }

        $sMessage = Lang::get('lovata.toolbox::lang.message.create', ['name' => self::CODE_ITEM]);

        if ($this->confirm($sMessage, true)) {
            $this->call('toolbox:create.item', ['data' => $this->arData]);
        }

        $sMessage = Lang::get('lovata.toolbox::lang.message.create', ['name' => self::CODE_STORE]);

        if ($this->confirm($sMessage, true)) {
            $this->call('toolbox:create.store', ['data' => $this->arData]);
        }

        $sMessage = Lang::get('lovata.toolbox::lang.message.create', ['name' => self::CODE_COLLECTION]);

        if ($this->confirm($sMessage, true)) {
            $this->call('toolbox:create.collection', ['data' => $this->arData]);
        }

        $sMessage = Lang::get('lovata.toolbox::lang.message.create', ['name' => self::CODE_COMPONENT_PAGE]);

        if ($this->confirm($sMessage, true)) {
            $this->call('toolbox:create.component.page', ['data' => $this->arData]);
        }

        $sMessage = Lang::get('lovata.toolbox::lang.message.create', ['name' => self::CODE_COMPONENT_DATA]);

        if ($this->confirm($sMessage, true)) {
            $this->call('toolbox:create.component.data', ['data' => $this->arData]);
        }

        $sMessage = Lang::get('lovata.toolbox::lang.message.create', ['name' => self::CODE_COMPONENT_LIST]);

        if ($this->confirm($sMessage, true)) {
            $this->call('toolbox:create.component.list', ['data' => $this->arData]);
        }

        $sMessage = Lang::get('lovata.toolbox::lang.message.create', ['name' => self::CODE_EVENT]);

        if ($this->confirm($sMessage, true)) {
            $this->call('toolbox:create.event.model', ['data' => $this->arData]);
        }
    }
}
