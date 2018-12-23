<?php namespace Lovata\Toolbox\Classes\Console;

use Lang;
use Lovata\Toolbox\Classes\Parser\ControllerFile;
use Lovata\Toolbox\Classes\Parser\ControllerListToolbarFile;
use Lovata\Toolbox\Classes\Parser\ControllerConfirmFormFile;
use Lovata\Toolbox\Classes\Parser\ControllerConfirmListFile;
use Lovata\Toolbox\Classes\Parser\ControllerCreateFile;
use Lovata\Toolbox\Classes\Parser\ControllerIndexFile;
use Lovata\Toolbox\Classes\Parser\ControllerPreviewFile;
use Lovata\Toolbox\Classes\Parser\ControllerUpdateFile;
use Lovata\Toolbox\Classes\Parser\ControllerConfirmFilterFile;
use Lovata\Toolbox\Classes\Parser\ControllerImport;
use Lovata\Toolbox\Classes\Parser\ControllerExport;
use Lovata\Toolbox\Classes\Parser\ControllerConfigImportExport;
use Lovata\Toolbox\Classes\Parser\ControllerReorder;
use Lovata\Toolbox\Classes\Parser\ControllerConfigReorder;
use Lovata\Toolbox\Classes\Parser\UpdatePluginYAML;

/**
 * Class CreateController
 * @package Lovata\Toolbox\Classes\Console
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateController extends CommonCreateFile
{
    /** @var string The console command name. */
    protected $name = 'toolbox:create.controller';
    /** @var string The console command description. */
    protected $description = 'Create a new controller.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        $this->setModel();
        $this->setController();
        $this->setFieldList(null, [self::CODE_ACTIVE, self::CODE_DEFAULT]);
        $this->setImportExportCSV();
        $this->setSorting([self::CODE_DEFAULT_SORTING]);
        $this->createAdditionalFile();
    }

    /**
     * Create file list
     */
    protected function createAdditionalFile()
    {
        $this->createFile(ControllerFile::class);
        $this->createFile(ControllerListToolbarFile::class);
        $this->createFile(ControllerConfirmFormFile::class);
        $this->createFile(ControllerConfirmListFile::class);
        $this->createFile(ControllerCreateFile::class);
        $this->createFile(ControllerIndexFile::class);
        $this->createFile(ControllerPreviewFile::class);
        $this->createFile(ControllerUpdateFile::class);
        $this->createFile(ControllerConfirmFilterFile::class);

        if ($this->checkEnableList(self::CODE_IMPORT_SVG)) {
            $this->createFile(ControllerImport::class);
        }

        if ($this->checkEnableList(self::CODE_EXPORT_SVG)) {
            $this->createFile(ControllerExport::class);
        }

        if ($this->checkEnableList(self::CODE_EMPTY_IMPORT_EXPORT_SVG)) {
            $this->createFile(ControllerConfigImportExport::class);
        }

        if ($this->checkEnableList(self::CODE_EMPTY_SORTABLE_NESTED_TREE)) {
            $this->createFile(ControllerReorder::class);
            $this->createFile(ControllerConfigReorder::class);
        }

        $this->updatePluginYAML();
    }

    /**
     * Update plugin.yaml
     */
    protected function updatePluginYAML()
    {
        $sMessage = Lang::get('lovata.toolbox::lang.message.add_side_menu');
        if ($this->confirm($sMessage, true)) {
            new UpdatePluginYAML($this->arData);
        }
    }
}
