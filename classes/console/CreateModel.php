<?php namespace Lovata\Toolbox\Classes\Console;

use Lang;
use Lovata\Toolbox\Classes\Parser\ModelFile;

/**
 * Class CreateModel
 * @package Lovata\Toolbox\Classes\Console
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateModel extends CommonCreateFile
{
    const CODE_TRAIT_VALIDATOR = 'trait_validation';

    /** @var string The console command name. */
    protected $name = 'toolbox.create.model';
    /** @var string The console command description. */
    protected $description = 'Create a new model.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        $this->arData['enable'][] = self::CODE_TRAIT_VALIDATOR;

        if (empty($this->arInoutData)) {
            $this->logoToolBox();
            $this->setAuthor();
            $this->setPlugin();
        }

        if (!$this->checkAddition(self::CODE_MODEL)) {
            $this->setModel();
        }

        if (!$this->checkAddition(self::CODE_CONTROLLER)) {
            $this->setController();
        }

        if (!$this->checkAddition(self::CODE_EMPTY_FIELD)) {
            $this->choiceFieldList();
            $this->addValidationData();
            $this->setAdditionalList();
        }

        $this->createFile(ModelFile::class);

        $this->callCommandList();
    }

    /**
     * Call command list
     */
    protected function callCommandList()
    {
        $sMessage = Lang::get('lovata.toolbox::lang.message.create', ['name' => self::CODE_CREATION_MIGRATION]);

        if ($this->confirm($sMessage, true)) {
            $this->call('toolbox.create.migration.create', ['data' => $this->arData]);
        }

        $sMessage = Lang::get('lovata.toolbox::lang.message.create', ['name' => self::CODE_CREATION_MODEL_COLUMNS]);

        if ($this->confirm($sMessage, true)) {
            $this->call('toolbox.create.model.columns', ['data' => $this->arData]);
        }

        $sMessage = Lang::get('lovata.toolbox::lang.message.create', ['name' => self::CODE_CREATION_MODEL_FIELDS]);

        if ($this->confirm($sMessage, true)) {
            $this->call('toolbox.create.model.fields', ['data' => $this->arData]);
        }
    }

    /**
     * Add validate data for $this->data
     */
    protected function addValidationData() {
        $bFieldName = in_array('name', $this->arData['enable']);
        $bFieldSlug = in_array('slug', $this->arData['enable']);

        if (empty($bFieldName) && empty($bFieldSlug)) {
            $this->arData['disable'][] = self::CODE_TRAIT_VALIDATOR;
        }
    }
}
