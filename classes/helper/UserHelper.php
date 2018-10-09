<?php namespace Lovata\Toolbox\Classes\Helper;

use System\Classes\PluginManager;
use October\Rain\Support\Traits\Singleton;

use Lovata\Toolbox\Classes\Helper\Users\BuddiesUserHelper;
use Lovata\Toolbox\Classes\Helper\Users\RainLabUserHelper;

/**
 * Class UserHelper
 * @package Lovata\Toolbox\Classes\Helper
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class UserHelper
{
    use Singleton;

    /** @var string */
    protected $sPluginName;

    /** @var BuddiesUserHelper|RainLabUserHelper */
    protected $obHelper;

    /**
     * Get auth user object
     * @return \Lovata\Buddies\Models\User|\RainLab\User\Models\User|null
     */
    public function getUser()
    {
        if (empty($this->obHelper)) {
            return null;
        }

        $sAuthFacadeClass = $this->obHelper->getAuthFacade();

        return $sAuthFacadeClass::getUser();
    }

    /**
     * Get user ID
     * @return int|null
     */
    public function getUserID()
    {
        $obUser = $this->getUser();
        if (empty($obUser)) {
            return null;
        }

        return $obUser->id;
    }

    /**
     * Create new user
     * @param array $arUserData
     * @param bool  $bActivate
     * @return \Lovata\Buddies\Models\User|\RainLab\User\Models\User|null
     */
    public function register($arUserData, $bActivate = false)
    {
        if (empty($this->obHelper)) {
            return null;
        }

        $sAuthFacadeClass = $this->obHelper->getAuthFacade();

        return $sAuthFacadeClass::register($arUserData, $bActivate);
    }

    /**
     * Find user by email
     * @param string $sEmail
     *
     * @return \Lovata\Buddies\Models\User|\RainLab\User\Models\User|null
     */
    public function findUserByEmail($sEmail)
    {
        if (empty($sEmail) || empty($this->obHelper)) {
            return null;
        }

        return $this->obHelper->findUserByEmail($sEmail);
    }

    /**
     * Get user model class name
     * @return string
     */
    public function getUserModel()
    {
        if (empty($this->obHelper)) {
            return null;
        }

        return $this->obHelper->getUserModel();
    }

    /**
     * Get user controller class name
     * @return string
     */
    public function getUserController()
    {
        if (empty($this->obHelper)) {
            return null;
        }

        return $this->obHelper->getUserController();
    }

    /**
     * Get auth facade class name
     * @return string
     */
    public function getAuthFacade()
    {
        if (empty($this->obHelper)) {
            return null;
        }

        return $this->obHelper->getAuthFacade();
    }

    /**
     * Get active plugin name
     * @return string
     */
    public function getPluginName()
    {
        return $this->sPluginName;
    }

    /**
     * Init data
     */
    protected function init()
    {
        $obPluginManager = PluginManager::instance();
        if ($obPluginManager->hasPlugin('Lovata.Buddies') && !$obPluginManager->isDisabled('Lovata.Buddies')) {
            $this->obHelper = app(BuddiesUserHelper::class);
            $this->sPluginName = 'Lovata.Buddies';
        } elseif ($obPluginManager->hasPlugin('RainLab.User') && !$obPluginManager->isDisabled('RainLab.User')) {
            $this->obHelper = app(RainLabUserHelper::class);
            $this->sPluginName = 'RainLab.User';
        }
    }
}
