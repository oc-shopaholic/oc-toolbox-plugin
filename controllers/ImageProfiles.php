<?php namespace Lovata\Toolbox\Controllers;

use BackendMenu;
use System\Classes\SettingsManager;
use Backend\Classes\Controller;

/**
 * Class ImageProfiles
 * @package Lovata\Toolbox\Controllers
 * @author  Pavel Lautsevich, p.lautsevich@lovata.com, LOVATA Group
 */
class ImageProfiles extends Controller
{
    public $implement = [
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.FormController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = ['toolbox-image-profiles'];

    /**
     * ImageProfiles constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('October.System', 'system', 'settings');
        SettingsManager::setContext('Lovata.Toolbox', 'image-profiles');
    }
}
