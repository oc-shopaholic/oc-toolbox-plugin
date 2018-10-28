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
        $this->arData = $this->argument('data');
        $arAdditionList   = array_get($this->arData, 'addition');
        $sModelLower      = array_get($this->arData, 'replace.' . self::PREFIX_LOWER . self::CODE_MODEL);
        $sModelStudly     = array_get($this->arData, 'replace.' . self::PREFIX_STUDLY . self::CODE_MODEL);
        $sControllerLower = array_get($this->arData, 'replace.' . self::PREFIX_LOWER . self::CODE_CONTROLLER);

        if (empty($this->arData)) {
            $this->logoToolBox();
            $this->choiceDeveloper();
            $this->setAuthor();
            $this->setPlugin();
        }

        if (empty($sModelLower) || empty($sModelStudly)) {
            $this->setModel();
        }

        if (empty($sControllerLower)) {
            $this->setController();
        }

        if (empty($arAdditionList) || (!empty($arAdditionList) && !in_array(self::CODE_EMPTY_FIELD, $arAdditionList))) {
            $this->choiceFieldList();
            $this->addValidationData();
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
    }

    /**
     * Add validate data for $this->data
     */
    protected function addValidationData() {
        $this->arData['enable'][] = self::CODE_TRAIT_VALIDATOR;

        $bFieldName = in_array('name', $this->arData['enable']);
        $bFieldSlug = in_array('slug', $this->arData['enable']);

        if (empty($bFieldName) && empty($bFieldSlug)) {
            $this->arData['disable'][] = self::CODE_TRAIT_VALIDATOR;
        }
    }
}