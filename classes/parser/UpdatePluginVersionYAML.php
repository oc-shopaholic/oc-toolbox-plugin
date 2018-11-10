<?php namespace Lovata\Toolbox\Classes\Parser;

use Yaml;
use October\Rain\Filesystem\Filesystem;

/**
 * Class UpdatePluginVersionYAML
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class UpdatePluginVersionYAML
{
    /** @var string */
    protected $sFile = 'version.yaml';
    /** @var string */
    protected $sPluginVersionPath = '';
    /** @var array */
    protected $arData = [];
    /** @var array */
    protected $arYAML = [];
    /** @var array */
    protected $arVersion = [];
    /** @var array  */
    protected $arMigrationList = [];
    /** @var string */
    protected $sVersion = '1.0.1';
    /** @var bool */
    protected $bVersionUp = true;
    /** @var bool */
    protected $bSave = true;

    /**
     * UpdatePluginVersion constructor.
     * @param array $arData
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __construct($arData = [])
    {
        $this->arData = $arData;
        $sAuthor = array_get($this->arData, 'replace.lower_author');
        $sPlugin = array_get($this->arData, 'replace.lower_plugin');

        if (empty($this->arData) || empty($sAuthor) || empty($sPlugin)) {
            return;
        }

        $this->sPluginVersionPath = plugins_path($sAuthor . '/' . $sPlugin . '/updates/' . $this->sFile);

        if (!file_exists($this->sPluginVersionPath)) {
            $obPluginYAMLFile = new PluginVersionFile($this->arData);
            $obPluginYAMLFile->create(true);
        }

        $this->processorYAML();
    }

    /**
     * Processing YAML
     */
    protected function processorYAML()
    {
        $this->arYAML = Yaml::parseFile($this->sPluginVersionPath);

        if (!array_key_exists($this->sVersion, $this->arYAML)) {
            return;
        }
        $sLowerAuthor     = array_get($this->arData, 'replace.lower_author');
        $sLowerPlugin     = array_get($this->arData, 'replace.lower_plugin');;
        $sLowerController = array_get($this->arData, 'replace.lower_controller');

        if (!empty($this->arYAML) && count($this->arYAML) > 0) {
            $this->arVersion = array_slice($this->arYAML, -1);
            $this->setVersion();
        } else {
            $this->arVersion[$this->sVersion] = [];
        }

        $this->setMigrationList($sLowerAuthor, $sLowerPlugin, $sLowerController);
        $this->setYAML();
        $this->save();
    }

    /**
     * Set version
     */
    protected function setVersion()
    {
        $bVersionUp = array_get($this->arData, 'addition.version_up');

        if (is_bool($bVersionUp)) {
            $this->bVersionUp = $bVersionUp;
        }

        $sCurrentVersion = key($this->arVersion);

        if (empty($sCurrentVersion) || !$this->bSave) {
            $this->bSave = false;

            return;
        }

        if (!$this->bVersionUp) {
            $this->sVersion = $sCurrentVersion;
        } else {
            $this->sVersion = $this->versionUp($sCurrentVersion);
        }
    }

    /**
     * Version Up
     * @param string $sCurrentVersion
     * @return string
     */
    protected function versionUp($sCurrentVersion)
    {
        $arValueList   = explode('.', $sCurrentVersion);
        $iValue        = (int) array_pop($arValueList);
        $arValueList[] = ++$iValue;

        return implode('.', $arValueList);
    }

    /**
     * Set migration list
     * @param string $sLowerAuthor
     * @param string $sLowerPlugin
     * @param string $sLowerController
     */
    protected function setMigrationList($sLowerAuthor, $sLowerPlugin, $sLowerController)
    {
        if (empty($sLowerAuthor) || empty($sLowerPlugin) || empty($sLowerController) || empty($this->arVersion) || !$this->bSave) {
            $this->bSave = false;

            return;
        }

        $sTable   =  $sLowerAuthor .'_' . $sLowerPlugin . '_' . $sLowerController;
        $sMessage = 'Create ' . $sTable . ' table.';
        $sFile    = 'create_table_' . $sLowerController . '.php';

        if (!$this->bVersionUp) {
            $this->arMigrationList   = array_pop($this->arVersion);
            $this->arMigrationList[] = $sMessage;
            $this->arMigrationList[] = $sFile;
        } else {
            $this->arMigrationList[] = $sMessage;
            $this->arMigrationList[] = $sFile;
        }
    }

    /**
     * Set YAML
     */
    protected function setYAML()
    {
        if (empty($this->arMigrationList) || empty($this->sVersion) || !$this->bSave) {
            $this->bSave = false;

            return;
        }

        $this->arYAML[$this->sVersion] = $this->arMigrationList;
    }

    /**
     * Save version.yaml
     */
    protected function save()
    {
        if ($this->bSave) {
            $sContent = Yaml::render($this->arYAML);
            $obFile = new Filesystem;
            $obFile->put($this->sPluginVersionPath, $sContent);
        }
    }
}