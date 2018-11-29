<?php namespace Lovata\Toolbox\Classes\Console;

use Lang;
use Lovata\Toolbox\Classes\Parser\ListStoreFile;
use Lovata\Toolbox\Classes\Parser\ActiveListStoreFile;
use Lovata\Toolbox\Classes\Parser\SortingListStoreFile;

/**
 * Class CreateStore
 * @package Lovata\Toolbox\Classes\Console
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateStore extends CommonCreateFile
{
    /** @var string The console command name. */
    protected $name = 'toolbox:create.store';
    /** @var string The console command description. */
    protected $description = 'Create a new store.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        $arEnableList = array_get($this->arInoutData, 'enable');

        if (empty($this->arInoutData)) {
            $this->logoToolBox();
            $this->setAuthor();
            $this->setPlugin();
        }

        if (!$this->checkAddition(self::CODE_MODEL)) {
            $this->setModel();
        }

        $this->createAdditionalFile(
            self::CODE_ACTIVE,
            ActiveListStoreFile::class,
            'lovata.toolbox::lang.message.active_list',
            $arEnableList
        );
        $this->createAdditionalFile(
            self::CODE_SORT,
            SortingListStoreFile::class,
            'lovata.toolbox::lang.message.sort_list',
            $arEnableList
        );

        $this->createFile(ListStoreFile::class);
    }

    /**
     * Create additional file
     * @param string $sCode
     * @param string $sClass
     * @param array $arEnableList
     * @param string $sCodeMessage
     */
    protected function createAdditionalFile($sCode, $sClass, $sCodeMessage, $arEnableList)
    {
        $this->arData['enable'][] = $sCode;

        if (!$this->checkAddition($sCode)) {
            $sMessage = Lang::get($sCodeMessage, ['class' => self::CODE_STORE]);
            if (!$this->confirm($sMessage, true)) {
                $this->arData['disable'][] = $sCode;
            } else {
                $this->createFile($sClass);
            }
        } elseif (!empty($arEnableList) && in_array($sCode, $arEnableList)) {
            $this->createFile($sClass);
        }
    }
}