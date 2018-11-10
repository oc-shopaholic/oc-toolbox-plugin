<?php namespace Lovata\Toolbox\Classes\Parser;

use Yaml;
use October\Rain\Filesystem\Filesystem;

/**
 * Class UpdatePluginYAML
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class UpdatePluginYAML
{
    const NAVIGATION  = 'navigation';
    const PERMISSIONS = 'permissions';
    const SIDE_MENU   = 'sideMenu';

    /** @var string */
    protected $sFile = 'plugin.yaml';
    /** @var string */
    protected $sPluginYAMLPath = '';
    /** @var array */
    protected $arData = [];
    /** @var array */
    protected $arYAML = [];
    /** @var array */
    protected $arMainMenu = [
        'label'       => '',
        'url'         => '',
        'icon'        => 'icon-smile-o',
        'permissions' => [],
        'sideMenu'    => [],
    ];
    /** @var array */
    protected $arSideMenu = [
        'label'       => '',
        'url'         => '',
        'icon'        => 'icon-paw',
        'permissions' => [],
    ];
    /** @var array */
    protected $arPermission = [
        'tab'   => '',
        'label' => '',
    ];
    /** @var bool */
    protected $bSave = true;

    /**
     * UpdatePluginYAML constructor.
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

        $this->sPluginYAMLPath = plugins_path($sAuthor . '/' . $sPlugin . '/' . $this->sFile);

        if (!file_exists($this->sPluginYAMLPath)) {
            $obPluginYAMLFile = new PluginYAMLFile($this->arData);
            $obPluginYAMLFile->create(true);
        }

        $this->processorYAML();
    }

    /**
     * Processing YAML
     */
    protected function processorYAML()
    {
        $this->arYAML = Yaml::parseFile($this->sPluginYAMLPath);

        $sLowerAuthor     = array_get($this->arData, 'replace.lower_author');
        $sLowerPlugin     = array_get($this->arData, 'replace.lower_plugin');;
        $sLowerController = array_get($this->arData, 'replace.lower_controller');
        $sLowerModel      = array_get($this->arData, 'replace.lower_model');

        $sKeyMainMenu   = $sLowerPlugin . '-menu-main';
        $sKeySideMenu   = $sLowerPlugin . '-menu-' . $sLowerController;
        $sKeyPermission = $sLowerPlugin . '-menu-' . $sLowerController;

        $arNavigation  = array_get($this->arYAML, self::NAVIGATION);
        $arPermissions = array_get($this->arYAML, self::PERMISSIONS);

        $arMainMenu    = array_get($arNavigation, $sKeyMainMenu);
        $arSideMenu    = array_get($arMainMenu, self::SIDE_MENU . '.' . $sKeySideMenu);
        $arPermission  = array_get($arPermissions, $sKeyPermission);

        if (empty($arNavigation) || count($arNavigation) == 0 || empty($arMainMenu)) {
            $this->setMainMenu($sLowerAuthor, $sLowerPlugin, $sLowerController);
        } else {
            $this->arMainMenu = $arMainMenu;
        }

        if (empty($arSideMenu)) {
            $this->setSideMenu($sLowerAuthor, $sLowerPlugin, $sLowerController);
        } else {
            $this->arSideMenu = $arSideMenu;
        }

        if (empty($arPermission)) {
            $this->setPermission($sLowerAuthor, $sLowerPlugin, $sLowerModel);
        } else {
            $this->arPermission = $arPermission;
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

        $sLabel      = $sLowerAuthor . '.' . $sLowerPlugin . '::lang.menu.main';
        $sURL        = $sLowerAuthor . '/' . $sLowerPlugin . '/' . $sLowerController;
        $sPermission = $sLowerPlugin . '-menu-*';

        $this->arMainMenu['label']         = $sLabel;
        $this->arMainMenu['url']           = $sURL;
        $this->arMainMenu['permissions'][] = $sPermission;
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

        $sLabel      = $sLowerAuthor . '.' . $sLowerPlugin . '::lang.menu.' . $sLowerController;
        $sURL        = $sLowerAuthor . '/' . $sLowerPlugin . '/' . $sLowerController;
        $sPermission = $sLowerPlugin . '-menu-' . $sLowerController;

        $this->arSideMenu['label']         = $sLabel;
        $this->arSideMenu['url']           = $sURL;
        $this->arSideMenu['permissions'][] = $sPermission;
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

        $sTab   = $sLowerAuthor . '.' . $sLowerPlugin . '::lang.tab.permissions';
        $sLabel = $sLowerAuthor . '.' . $sLowerPlugin . '::lang.permission.' . $sLowerModel;

        array_set($this->arPermission, 'tab', $sTab);
        array_set($this->arPermission, 'label', $sLabel);
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

        array_set($this->arMainMenu, self::SIDE_MENU .'.' . $sKeySideMenu, $this->arSideMenu);
        array_set($this->arYAML, self::NAVIGATION . '.' . $sKeyMainMenu, $this->arMainMenu);
        array_set($this->arYAML, self::PERMISSIONS . '.' . $sKeyPermission, $this->arPermission);
    }

    /**
     * Save plugin.yaml
     */
    protected function save()
    {
        if ($this->bSave) {
            $sContent = Yaml::render($this->arYAML);
            $obFile = new Filesystem;
            $obFile->put($this->sPluginYAMLPath, $sContent);
        }
    }
}