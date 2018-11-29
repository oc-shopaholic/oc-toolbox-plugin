<?php namespace Lovata\Toolbox\Classes\Console;

use Lovata\Toolbox\Classes\Parser\ModelFieldFile;

/**
 * Class CreateModelField
 * @package Lovata\Toolbox\Classes\Console
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateModelField extends CommonCreateFile
{
    /** @var string The console command name. */
    protected $name = 'toolbox:create.model.fields';
    /** @var string The console command description. */
    protected $description = 'Create a new fields model.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        if (empty($this->arInoutData)) {
            $this->logoToolBox();
            $this->setAuthor();
            $this->setPlugin();
        }

        if (!$this->checkAddition(self::CODE_MODEL)) {
            $this->setModel();
        }

        if (!$this->checkAddition(self::CODE_EMPTY_FIELD)) {
            $this->choiceFieldList();
        }

        $this->createFile(ModelFieldFile::class);
    }
}
