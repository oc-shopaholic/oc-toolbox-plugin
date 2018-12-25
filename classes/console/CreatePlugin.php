<?php namespace Lovata\Toolbox\Classes\Console;

use Lang;
use Lovata\Toolbox\Classes\Parser\Create\PluginPHPCreateFile;
use Lovata\Toolbox\Classes\Parser\Create\PluginYAMLCreateFile;
use Lovata\Toolbox\Classes\Parser\Create\PluginVersionCreateFile;
use Lovata\Toolbox\Classes\Parser\Create\PluginLangCreateFile;

/**
 * Class CreatePlugin
 * @package Lovata\Toolbox\Classes\Console
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreatePlugin extends CommonCreateFile
{
    /** @var string The console command name. */
    protected $name = 'toolbox:create.plugin';
    /** @var string The console command description. */
    protected $description = 'Create a new plugin.';
    /** @var array */
    protected $arLangList = [
        'en',
        'ru',
        'fr',
        'de',
        'ja',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->initData();
        $this->setLogo();
        $this->setDeveloper();

        if (!$this->checkAdditionList(self::CODE_AUTHOR) || !$this->checkAdditionList(self::CODE_PLUGIN)) {
            $this->setAuthor();
            $this->setPlugin();
        }

        if ($this->checkPluginExist()) {
            return;
        }

        $this->setLangList();
        $this->createFile(PluginPHPCreateFile::class);
        $this->createFile(PluginYAMLCreateFile::class);
        $this->createFile(PluginVersionCreateFile::class);
        $this->createLangFile();
    }

    /**
     * Check plugin exist
     * @return bool
     */
    protected function checkPluginExist()
    {
        $bResult = true;
        $sAuthor = array_get($this->arData, 'replace.lower_author');
        $sPlugin = array_get($this->arData, 'replace.lower_plugin');

        if (empty($sAuthor) || empty($sPlugin)) {
            return $bResult;
        }

        $sPluginPHPPath  = plugins_path($sAuthor.'/'.$sPlugin.'/Plugin.php');
        $sPluginYAMLPath = plugins_path($sAuthor.'/'.$sPlugin.'/plugin.yaml');

        if (!file_exists($sPluginPHPPath) && !file_exists($sPluginYAMLPath)) {
            $bResult = false;
        }

        return $bResult;
    }

    /**
     * Set lang list
     */
    protected function setLangList()
    {
        if (empty($this->arLangList)) {
            return;
        }

        $sMessage = Lang::get('lovata.toolbox::lang.message.choice_lang_list');

        $this->arLangList = $this->choice($sMessage, $this->arLangList, null, null, true);
    }

    /**
     * Create lang file
     */
    protected function createLangFile()
    {
        if (empty($this->arLangList)) {
            return;
        }

        foreach ($this->arLangList as $sLang) {
            array_set($this->arData, 'replace.lang', $sLang);
            $this->createFile(PluginLangCreateFile::class);
        }
    }
}
