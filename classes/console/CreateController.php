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

/**
 * Class CreateController
 * @package Lovata\Toolbox\Classes\Console
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateController extends CommonCreateFile
{
    /** @var string The console command name. */
    protected $name = 'toolbox.create.controller';
    /** @var string The console command description. */
    protected $description = 'Create a new controller.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        $sModelLower       = array_get($this->arInoutData, 'replace.' . self::PREFIX_LOWER . self::CODE_MODEL);
        $sModelStudly      = array_get($this->arInoutData, 'replace.' . self::PREFIX_STUDLY . self::CODE_MODEL);
        $sControllerLower  = array_get($this->arInoutData, 'replace.' . self::PREFIX_LOWER . self::CODE_CONTROLLER);
        $sControllerStudly = array_get($this->arInoutData, 'replace.' . self::PREFIX_STUDLY . self::CODE_CONTROLLER);

        if (empty($this->arInoutData)) {
            $this->logoToolBox();
            $this->setAuthor();
            $this->setPlugin();
        }

        if (empty($sModelLower) || empty($sModelStudly)) {
            $this->setModel();
        }

        if (empty($sControllerLower) || empty($sControllerStudly)) {
            $this->setController();
        }

        if (!$this->checkAddition(self::CODE_ACTIVE)) {
            $this->arData['enable'][] = self::CODE_ACTIVE;
            $sMessage = Lang::get('lovata.toolbox::lang.message.filter_active');
            if (!$this->confirm($sMessage, true)) {
                $this->arData['disable'][] = self::CODE_ACTIVE;
            }
        };

        $this->createFileList();
    }

    /**
     * Create file list
     */
    protected function createFileList ()
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
    }
}