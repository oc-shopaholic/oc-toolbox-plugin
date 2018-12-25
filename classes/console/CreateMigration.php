<?php namespace Lovata\Toolbox\Classes\Console;

use Lang;
use Lovata\Toolbox\Classes\Parser\Create\MigrationCreateFile;
use Lovata\Toolbox\Classes\Parser\Update\PluginVersionYAMLUpdateFile;

/**
 * Class CreateMigration
 * @package Lovata\Toolbox\Classes\Console
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateMigration extends CommonCreateFile
{
    /** @var string The console command name. */
    protected $name = 'toolbox:create.migration.create';
    /** @var string The console command description. */
    protected $description = 'Create a new creation migration.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        $this->setController();
        $this->setFieldList([self::CODE_PREVIEW_IMAGE, self::CODE_IMAGES, self::CODE_FILE]);
        $this->setSorting([self::CODE_DEFAULT_SORTING]);
        $this->createFile(MigrationCreateFile::class);
        $this->updatePluginVersionYAML();
    }

    /**
     * Update version.yaml
     */
    protected function updatePluginVersionYAML()
    {
        $sMessage = Lang::get('lovata.toolbox::lang.message.version_up');
        $bConfirm = $this->confirm($sMessage, false);
        array_set($this->arData, 'addition.version_up', $bConfirm);
        $obUpdate = new PluginVersionYAMLUpdateFile($this->arData);
        $obUpdate->update();
    }
}
