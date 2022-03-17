<?php namespace Lovata\Toolbox\Classes\Api\Type;

use Event;
use Closure;
use Illuminate\Support\Arr;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ObjectType;

use Lovata\Toolbox\Classes\Api\PermissionContainer;
use Lovata\Toolbox\Classes\Helper\UserHelper;

use October\Rain\Extension\ExtendableTrait;
use October\Rain\Support\Traits\Singleton;

/**
 * Class AbstractApiType
 * @package Lovata\Toolbox\Classes\Api\Type
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractApiType
{
    use ExtendableTrait;
    use Singleton;

    const PERMISSION = '';
    const TYPE_ALIAS = '';
    const IS_INPUT_TYPE = false;
    const EVENT_EXTEND_FIELD_LIST = 'lovata.api.extend.fields';
    const EVENT_EXTEND_PERMISSION_LIST = 'lovata.api.extend.permissions';
    const EVENT_EXTEND_ACCESS_LOGIC = 'lovata.api.extend.access_logic';

    /**
     * @var array Behaviors implemented by this class.
     */
    public $implement;

    /** @var ObjectType */
    protected $obTypeObject;

    /** @var \Lovata\Buddies\Models\User|\RainLab\User\Models\User|null */
    protected $obClient = null;

    /** @var array $arFieldList */
    protected $arFieldList = [];

    /**
     * @throws \Exception
     */
    protected function init()
    {
        $this->arFieldList = $this->getFieldList();
        $this->extendableConstruct();
        $this->fireEventExtendFields();
        $this->initClient();
        $this->initClientPermissions();
    }

    /**
     * Return new object type
     * @return ObjectType
     */
    public function getTypeObject()
    {
        if (empty($this->obTypeObject)) {
            if (static::IS_INPUT_TYPE) {
                $this->obTypeObject = new InputObjectType($this->getTypeConfig());
            } else {
                $this->obTypeObject = new ObjectType($this->getTypeConfig());
            }
        }

        return $this->obTypeObject;
    }

    /**
     * @return AbstractApiType
     */
    public static function make()
    {
        return static::instance();
    }

    /**
     * Init client
     * @return \Lovata\Buddies\Models\User|\RainLab\User\Models\User|null
     */
    protected function initClient()
    {
        return $this->obClient = UserHelper::instance()->getUser();
    }

    /**
     * Check permissions
     * @param array $arPermissionList
     * @param null $obObject
     * @param null $arActions
     * @return bool
     */
    protected function checkAccess(array $arPermissionList, $obObject = null, $arActions = null): bool
    {
        $sTypePermissions = implode('.', $arPermissionList);
        $arClientPermissionList = PermissionContainer::instance()->getPermissions();

        if (!Arr::get($arClientPermissionList, $sTypePermissions)) {
            return false;
        }

        $arEventData = [
            'permissions' => $sTypePermissions,
            'subject'     => $this->obClient,
            'object'      => $obObject,
            'action'      => $arActions
        ];

        $mEventAccessLogic = Event::fire(self::EVENT_EXTEND_ACCESS_LOGIC, $arEventData, true);

        $bResult = (is_bool($mEventAccessLogic)) ? $mEventAccessLogic : true;

        return $bResult;
    }

    /**
     * Add fields
     * @param array $arFieldList
     * @return void
     */
    public function addFields(array $arFieldList)
    {
        $this->arFieldList = array_merge($this->arFieldList, $arFieldList);
    }

    /**
     * Remove fields
     * @param array $arFieldList
     * @return void
     */
    public function removeFields(array $arFieldList)
    {
        if (empty($arFieldList)) {
            return;
        }

        foreach ($arFieldList as $sKey) {
            unset($this->arFieldList[$sKey]);
        }
    }

    /**
     * Get type fields
     * @return array
     */
    abstract protected function getFieldList(): array;

    /**
     * Get type config
     * @return array
     */
    protected function getTypeConfig(): array
    {
        $arTypeConfig = [
            'name'   => static::TYPE_ALIAS,
            'fields' => $this->arFieldList,
        ];

        return $arTypeConfig;
    }

    /**
     * Get resolve method for type
     * @return callable|null
     */
    protected function getResolveMethod(): ?callable
    {
        return null;
    }

    /**
     * Get config for "args" attribute
     * @return array|null
     */
    protected function getArguments(): ?array
    {
        return null;
    }

    /**
     * Fire event extend fields
     * @return void
     */
    protected function fireEventExtendFields()
    {
        Event::fire(self::EVENT_EXTEND_FIELD_LIST, [$this]);
    }

    /**
     * @param string $sTypeAlias
     *
     * @return \GraphQL\Type\Definition\ObjectType|null
     *
     * @throws \GraphQL\Error\Error
     */
    public function getRelationType(string $sTypeAlias)
    {
        return TypeFactory::instance()->get($sTypeAlias);
    }

    /**
     * Init client permissions
     * @return void
     */
    protected function initClientPermissions()
    {
        switch (static::PERMISSION) {
            case PermissionContainer::PERMISSION_CODE_GUEST:
                PermissionContainer::instance()->addGuestPermissions([static::TYPE_ALIAS => 1]);
                break;
            case PermissionContainer::PERMISSION_CODE_USER:
                PermissionContainer::instance()->addUserPermissions([static::TYPE_ALIAS => 1]);
                break;
        }
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

    /**
     * Returns callback function in resolve methods for fields
     * @param string $sMethod
     * @return callable
     */
    protected function returnCallback(string $sMethod): callable
    {
        return function ($obElement, $arArgumentList) use ($sMethod) {
            if (!empty($arArgumentList)) {
                return $this->$sMethod($obElement, $arArgumentList);
            } elseif (!empty($obElement)) {
                return $this->$sMethod($obElement);
            }

            return $this->$sMethod();
        };
    }
}
