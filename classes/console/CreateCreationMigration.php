<?php namespace Lovata\Toolbox\Classes\Console;

use Lovata\Toolbox\Classes\Parser\CreationMigrationFile;

/**
 * Class CreateCreationMigration
 * @package Lovata\Toolbox\Classes\Console
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateCreationMigration extends CommonCreateFile
{
    /** @var string The console command name. */
    protected $name = 'toolbox.create.migration.create';
    /** @var string The console command description. */
    protected $description = 'Create a new creation migration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->arData = $this->argument('data');
        $arAdditionList   = array_get($this->arData, 'addition');
        $sControllerLower = array_get($this->arData, 'replace.' . self::PREFIX_LOWER . self::CODE_CONTROLLER);

        if (empty($this->arData)) {
            $this->logoToolBox();
            $this->choiceDeveloper();
            $this->setAuthor();
            $this->setPlugin();
        }

        if (empty($sControllerLower)) {
            $this->setController();
        }

        if (empty($arAdditionList) || (!empty($arAdditionList) && !in_array(self::CODE_EMPTY_FIELD, $arAdditionList))) {
            $this->choiceFieldList([
                'preview_image',
                'images',
            ]);
        };

        $this->createFile(CreationMigrationFile::class);
    }
}