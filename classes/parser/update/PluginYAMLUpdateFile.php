<?php namespace Lovata\Toolbox\Classes\Parser\Update;

use Yaml;
use Lovata\Toolbox\Classes\Parser\Create\PluginYAMLCreateFile;

/**
 * Class PluginYAMLUpdateFile
 * @package Lovata\Toolbox\Classes\Parser\Update
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class PluginYAMLUpdateFile extends CommonUpdateFile
{
    const PLUGIN_NAVIGATION  = 'navigation';
    const PLUGIN_PERMISSIONS = 'permissions';
    const PLUGIN_SIDE_MENU   = 'sideMenu';

    /** @var string */
    protected $sFilePath = '/{{lower_author}}/{{lower_plugin}}/plugin.yaml';
    /** @var array */
    protected $arYAML = [];
    /** @var array */
    protected $arMainMenu = [];
    /** @var array */
    protected $arSideMenu = [];
    /** @var array */
    protected $arPermission = [];

    /**
     * Class create file
     * @return string
     */
    protected function classCreateFile()
    {
        return PluginYAMLCreateFile::class;
    }

    /**
     * Update
     */
    public function update()
    {
        if (!$this->bUpdate || !isset($this->sFilePath) || empty($this->sFilePath)) {
            return;
        }

        $this->arYAML = Yaml::parseFile($this->sFilePath);

        $sLowerAuthor     = array_get($this->arData, 'replace.lower_author');
        $sLowerPlugin     = array_get($this->arData, 'replace.lower_plugin');
        $sLowerController = array_get($this->arData, 'replace.lower_controller');
        $sLowerModel      = array_get($this->arData, 'replace.lower_model');

        $sKeyMainMenu   = $sLowerPlugin.'-menu-'.'main';
        $sKeySideMenu   = $sLowerPlugin.'-menu-'.$sLowerController;
        $sKeyPermission = $sLowerPlugin.'-menu-'.$sLowerController;

        $arNavigation  = array_get($this->arYAML, self::PLUGIN_NAVIGATION);
        $arPermissions = array_get($this->arYAML, self::PLUGIN_PERMISSIONS);

        $arMainMenuCurrent   = array_get($arNavigation, $sKeyMainMenu);
        $arSideMenuCurrent   = array_get($arMainMenuCurrent, self::PLUGIN_SIDE_MENU.'.'.$sKeySideMenu);
        $arPermissionCurrent = array_get($arPermissions, $sKeyPermission);

        if (empty($arNavigation) || count($arNavigation) == 0 || empty($arMainMenuCurrent)) {
            $this->setMainMenu($sLowerAuthor, $sLowerPlugin, $sLowerController);
        } else {
            $this->arMainMenu = $arMainMenuCurrent;
        }

        if (empty($arSideMenuCurrent)) {
            $this->setSideMenu($sLowerAuthor, $sLowerPlugin, $sLowerController);
        } else {
            $this->arSideMenu = $arSideMenuCurrent;
        }

        if (empty($arPermissionCurrent)) {
            $this->setPermission($sLowerAuthor, $sLowerPlugin, $sLowerModel);
        } else {
            $this->arPermission = $arPermissionCurrent;
        }

        $this->setYAML($sKeyMainMenu, $sKeySideMenu, $sKeyPermission);
        $this->save();
    }

    /**
     * Set main menu
     * @param string $sLowerAuthor
     * @param string $sLowerPlugin
     * @param string $sLowerController
     */
    protected function setMainMenu($sLowerAuthor = '', $sLowerPlugin = '', $sLowerController = '')
    {
        if (empty($sLowerAuthor) || empty($sLowerPlugin) || empty($sLowerController) || !$this->bSave) {
            $this->bSave = false;

            return;
        }

        array_set($this->arMainMenu, 'label', $sLowerAuthor.'.'.$sLowerPlugin.'::lang.menu.main');
        array_set($this->arMainMenu, 'url', $sLowerAuthor.'/'.$sLowerPlugin.'/'.$sLowerController);
        array_set($this->arMainMenu, 'permissions', [$sLowerPlugin.'-menu-'.'*']);
        array_set($this->arMainMenu, 'icon', 'icon-paw');
    }

    /**
     * Set side menu
     * @param string $sLowerAuthor
     * @param string $sLowerPlugin
     * @param string $sLowerController
     */
    protected function setSideMenu($sLowerAuthor, $sLowerPlugin, $sLowerController)
    {
        if (empty($sLowerAuthor) || empty($sLowerPlugin) || empty($sLowerController) || !$this->bSave) {
            $this->bSave = false;

            return;
        }

        array_set($this->arSideMenu, 'label', $sLowerAuthor.'.'.$sLowerPlugin.'::lang.menu.' . $sLowerController);
        array_set($this->arSideMenu, 'url', $sLowerAuthor.'/'.$sLowerPlugin.'/'.$sLowerController);
        array_set($this->arSideMenu, 'permissions', [$sLowerPlugin.'-menu-'.$sLowerController]);
        array_set($this->arSideMenu, 'icon', 'icon-paw');
    }

    /** Set permission
     * @param string $sLowerAuthor
     * @param string $sLowerPlugin
     * @param string $sLowerModel
     */
    protected function setPermission($sLowerAuthor, $sLowerPlugin, $sLowerModel)
    {
        if (empty($sLowerAuthor) || empty($sLowerPlugin) || empty($sLowerModel) || !$this->bSave) {
            $this->bSave = false;

            return;
        }

        array_set($this->arPermission, 'label', $sLowerAuthor.'.'.$sLowerPlugin.'::lang.permission.'.$sLowerModel);
        array_set($this->arPermission, 'tab', $sLowerAuthor.'.'.$sLowerPlugin.'::lang.tab.permissions');
    }

    /**
     * Set YAML
     * @param string $sKeyMainMenu
     * @param string $sKeySideMenu
     * @param string $sKeyPermission
     */
    protected function setYAML($sKeyMainMenu, $sKeySideMenu, $sKeyPermission)
    {
        if (empty($sKeyMainMenu) || empty($sKeySideMenu) || empty($sKeyPermission) || !$this->bSave) {
            $this->bSave = false;

            return;
        }

        array_set($this->arMainMenu, self::PLUGIN_SIDE_MENU.'.'.$sKeySideMenu, $this->arSideMenu);
        array_set($this->arYAML, self::PLUGIN_NAVIGATION.'.'.$sKeyMainMenu, $this->arMainMenu);
        array_set($this->arYAML, self::PLUGIN_PERMISSIONS.'.'.$sKeyPermission, $this->arPermission);
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
