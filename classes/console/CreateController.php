<?php namespace Lovata\Toolbox\Classes\Console;

use Lang;
use Lovata\Toolbox\Classes\Parser\Create\ControllerCreateCreateFile;
use Lovata\Toolbox\Classes\Parser\Create\ControllerCreateFile;
use Lovata\Toolbox\Classes\Parser\Create\ControllerListToolbarCreateFile;
use Lovata\Toolbox\Classes\Parser\Create\ControllerConfirmFormCreateFile;
use Lovata\Toolbox\Classes\Parser\Create\ControllerConfirmListCreateFile;
use Lovata\Toolbox\Classes\Parser\Create\ControllerIndexCreateFile;
use Lovata\Toolbox\Classes\Parser\Create\ControllerPreviewCreateFile;
use Lovata\Toolbox\Classes\Parser\Create\ControllerUpdateCreateFile;
use Lovata\Toolbox\Classes\Parser\Create\ControllerConfirmFilterCreateFile;
use Lovata\Toolbox\Classes\Parser\Create\ControllerImportCreateFile;
use Lovata\Toolbox\Classes\Parser\Create\ControllerExportCreateFile;
use Lovata\Toolbox\Classes\Parser\Create\ControllerConfigImportExportCreateFile;
use Lovata\Toolbox\Classes\Parser\Create\ControllerReorderCreateFile;
use Lovata\Toolbox\Classes\Parser\Create\ControllerConfigReorderCreateFile ;
use Lovata\Toolbox\Classes\Parser\Update\PluginYAMLUpdateFile;
use Lovata\Toolbox\Traits\Console\UpdateLangFile;

/**
 * Class CreateController
 * @package Lovata\Toolbox\Classes\Console
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateController extends CommonCreateFile
{
    use UpdateLangFile;

    /** @var string The console command name. */
    protected $name = 'toolbox:create.controller';
    /** @var string The console command description. */
    protected $description = 'Create a new controller.';
    /** @var array */
    protected $arLangData = [
        'menu' => [
            '{{lower_controller}}' => '{{studly_model}} list',
        ],
        'permission' => [
            '{{lower_model}}' => 'Manage {{lower_model}}',
        ],
        '{{lower_model}}' => [
            'name' => '{{lower_model}}',
            'list_title' => '{{studly_model}} list',
        ],
    ];

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
        $this->createFile(ControllerCreateFile::class);
        $this->createFile(ControllerListToolbarCreateFile::class);
        $this->createFile(ControllerConfirmFormCreateFile::class);
        $this->createFile(ControllerConfirmListCreateFile::class);
        $this->createFile(ControllerCreateCreateFile::class);
        $this->createFile(ControllerIndexCreateFile::class);
        $this->createFile(ControllerPreviewCreateFile::class);
        $this->createFile(ControllerUpdateCreateFile::class);
        $this->createFile(ControllerConfirmFilterCreateFile::class);

        if ($this->checkEnableList(self::CODE_IMPORT_SVG)) {
            $this->createFile(ControllerImportCreateFile::class);
        }

        if ($this->checkEnableList(self::CODE_EXPORT_SVG)) {
            $this->createFile(ControllerExportCreateFile::class);
        }

        if ($this->checkEnableList(self::CODE_EMPTY_IMPORT_EXPORT_SVG)) {
            $this->createFile(ControllerConfigImportExportCreateFile::class);
        }

        if ($this->checkEnableList(self::CODE_EMPTY_SORTABLE_NESTED_TREE)) {
            $this->createFile(ControllerReorderCreateFile::class);
            $this->createFile(ControllerConfigReorderCreateFile::class);
        }

        $this->updatePluginYAML();
        $this->updatePluginLang($this->arLangData);
    }

    /**
     * Update plugin.yaml
     */
    protected function updatePluginYAML()
    {
        $sMessage = Lang::get('lovata.toolbox::lang.message.add_side_menu');
        if ($this->confirm($sMessage, true)) {
            $obUpdate = new PluginYAMLUpdateFile($this->arData);
            $obUpdate->update();
        }
    }
}
