<?php namespace Lovata\Toolbox\Classes\Parser\Update;

use Yaml;
use Lovata\Toolbox\Classes\Parser\Create\PluginVersionCreateFile;

/**
 * Class PluginVersionYAMLUpdateFile
 * @package Lovata\Toolbox\Classes\Parser\Update
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class PluginVersionYAMLUpdateFile extends CommonUpdateFile
{
    /** @var string */
    protected $sFilePath = '/{{lower_author}}/{{lower_plugin}}/updates/version.yaml';
    /** @var array */
    protected $arYAML = [];
    /** @var array */
    protected $arVersion = [];
    /** @var array  */
    protected $arMigrationList = [];
    /** @var string */
    protected $sVersion = '1.0.0';
    /** @var bool */
    protected $bVersionUp = true;

    /**
     * Class create file
     * @return string
     */
    protected function classCreateFile()
    {
        return PluginVersionCreateFile::class;
    }

    /**
     * Update file
     */
    public function update()
    {
        if (!$this->bUpdate || !isset($this->sFilePath) || empty($this->sFilePath)) {
            return;
        }

        $this->arYAML = Yaml::parseFile($this->sFilePath);

        if (!array_key_exists($this->sVersion, $this->arYAML)) {
            return;
        }

        $sLowerAuthor     = array_get($this->arData, 'replace.lower_author');
        $sLowerPlugin     = array_get($this->arData, 'replace.lower_plugin');
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

        $sMessage = 'Create tables.';
        $sFile    = 'create_table_'.$sLowerController.'.php';

        if (!$this->bVersionUp) {
            $this->arMigrationList   = array_pop($this->arVersion);
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
        if (!$this->bSave || empty($this->arYAML) || !isset($this->sFilePath) || empty($this->sFilePath)) {
            return;
        }

        $sContent = Yaml::render($this->arYAML);
        $this->obFile->put($this->sFilePath, $sContent);
    }
}
