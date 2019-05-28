<?php namespace Lovata\Toolbox\Classes\Console;

use Lang;
use Lovata\Toolbox\Classes\Parser\Create\ModelCreateFile;

/**
 * Class CreateModel
 * @package Lovata\Toolbox\Classes\Console
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateModel extends CommonCreateFile
{
    /** @var string The console command name. */
    protected $name = 'toolbox:create.model';
    /** @var string The console command description. */
    protected $description = 'Create a new model.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        $this->setModel();
        $this->setController();
        $this->setFieldList();
        $this->setImportExportCSV();
        $this->setSorting([self::CODE_DEFAULT_SORTING]);
        $this->setAdditionList(self::CODE_COMMAND_PARENT);
        $this->createFile(ModelCreateFile::class);
        $this->callCommandList();
    }

    /**
     * Call command list
     */
    protected function callCommandList()
    {
        $sMessage = Lang::get('lovata.toolbox::lang.message.create', ['name' => self::CODE_CREATION_MIGRATION]);

        if ($this->confirm($sMessage, true)) {
            $this->call('toolbox:create.migration.create', ['data' => $this->arData]);
        }

        $sMessage = Lang::get('lovata.toolbox::lang.message.create', ['name' => self::CODE_CREATION_MODEL_COLUMNS]);

        if ($this->confirm($sMessage, true)) {
            $this->call('toolbox:create.model.columns', ['data' => $this->arData]);
        }

        $sMessage = Lang::get('lovata.toolbox::lang.message.create', ['name' => self::CODE_CREATION_MODEL_FIELDS]);

        if ($this->confirm($sMessage, true)) {
            $this->call('toolbox:create.model.fields', ['data' => $this->arData]);
        }
    }
}
