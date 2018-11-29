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

        if (!$this->checkAddition(self::CODE_ACTIVE)) {
            $this->arData['enable'][] = self::CODE_ACTIVE;
            $sMessage = Lang::get('lovata.toolbox::lang.message.filter_active');
            if (!$this->confirm($sMessage, true)) {
                $this->arData['disable'][] = self::CODE_ACTIVE;
            }
        };

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
