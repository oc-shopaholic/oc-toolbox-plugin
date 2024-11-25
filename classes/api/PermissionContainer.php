<?php namespace Lovata\Toolbox\Classes\Api;

use Closure;
use Lovata\Toolbox\Classes\Helper\UserHelper;
use October\Rain\Extension\ExtendableTrait;
use October\Rain\Support\Traits\Singleton;

/**
 * Class PermissionContainer
 * @package Lovata\Toolbox\Classes\Api
 */
class PermissionContainer
{
    use ExtendableTrait;
    use Singleton;

    const PERMISSION_CODE_GUEST = 'guest';
    const PERMISSION_CODE_USER = 'user';

    /**
     * @var array Behaviors implemented by this class.
     */
    public $implement;

    /** @var \Lovata\Buddies\Models\User|\RainLab\User\Models\User|null */
    protected $obUser = null;

    /** @var array $arGuestPermissionList */
    protected $arGuestPermissionList = [];

    /** @var array $arUserPermissionList */
    protected $arUserPermissionList = [];

    /**
     * Add guest permissions
     * @param $arPermissionList
     * @return void
     */
    public function addGuestPermissions($arPermissionList)
    {
        $this->arGuestPermissionList = array_merge($this->arGuestPermissionList, $arPermissionList);
    }

    /**
     * Add user permissions
     * @param $arPermissionList
     * @return void
     */
    public function addUserPermissions($arPermissionList)
    {
        $this->arUserPermissionList = array_merge($this->arUserPermissionList, $arPermissionList);
    }

    /**
     * Remove guest permissions
     * @param $arPermissionList
     * @return void
     */
    public function removeGuestPermissions($arPermissionList)
    {
        if (empty($arPermissionList)) {
            return;
        }

        foreach ($arPermissionList as $sKey) {
            unset($this->arGuestPermissionList[$sKey]);
        }
    }

    /**
     * Remove user permissions
     * @param $arPermissionList
     * @return void
     */
    public function removeUserPermissions($arPermissionList)
    {
        if (empty($arPermissionList)) {
            return;
        }

        foreach ($arPermissionList as $sKey) {
            unset($this->arUserPermissionList[$sKey]);
        }
    }

    /**
     * Get client permission list
     * @return array
     */
    public function getPermissions(): array
    {
        if (empty($this->obUser)) {
            return $this->arGuestPermissionList;
        }

        //TODO: ADD events

        return array_merge($this->arGuestPermissionList, $this->arUserPermissionList);
    }

    /**
     * @throws \Exception
     */
    protected function init()
    {
        $this->initUser();
        $this->initUserPermissions();
        $this->extendableConstruct();
    }

    /**
     * Init user
     * @return void
     */
    protected function initUser()
    {
        $this->obUser = UserHelper::instance()->getUser();
    }

    /**
     * Init user permissions
     * @return void
     */
    protected function initUserPermissions()
    {
        if (empty($this->obUser)) {
            return;
        }

        $this->arUserPermissionList = (array) $this->obUser->permissions;
    }

    /**
     * @param $name
     * @return string
     */
    public function __get($name)
    {
        return $this->extendableGet($name);
    }

    /**
     * @param $name
     * @param $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->extendableSet($name, $value);
    }

    /**
     * @param $name
     * @param $params
     * @return mixed
     */
    public function __call($name, $params)
    {
        return $this->extendableCall($name, $params);
    }

    /**
     * @param $name
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public static function __callStatic($name, $params)
    {
        return self::extendableCallStatic($name, $params);
    }

    /**
     * Extend this object properties upon construction.
     * @param Closure $callback
     * @return void
     */
    public static function extend(Closure $callback)
    {
        self::extendableExtendCallback($callback);
    }
}
