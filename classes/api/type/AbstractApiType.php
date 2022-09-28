<?php namespace Lovata\Toolbox\Classes\Api\Type;

use Lang;
use Event;
use Closure;
use Illuminate\Support\Arr;

use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\UnionType;

use Lovata\Toolbox\Classes\Api\PermissionContainer;
use Lovata\Toolbox\Classes\Api\Response\ApiDataResponse;
use Lovata\Toolbox\Classes\Api\Type\Custom\PaginationInfoType;
use Lovata\Toolbox\Classes\Api\Type\Enum\ResizeImageModeEnumType;
use Lovata\Toolbox\Classes\Api\Type\Input\FilterCollectionInputType;
use Lovata\Toolbox\Classes\Api\Type\Input\GetNearestElementCollectionInputType;
use Lovata\Toolbox\Classes\Api\Type\Input\PaginateInputType;
use Lovata\Toolbox\Classes\Api\Type\Input\ResizeImageInputType;
use Lovata\Toolbox\Classes\Api\Type\Interfaces\FileInterfaceType;
use Lovata\Toolbox\Classes\Api\Type\Custom\FileType;
use Lovata\Toolbox\Classes\Api\Type\Custom\ImageFileType;
use Lovata\Toolbox\Classes\Helper\UserHelper;

use October\Rain\Extension\ExtendableTrait;
use October\Rain\Support\Traits\Singleton;
use SystemException;

/**
 * Class AbstractApiType
 * @package Lovata\Toolbox\Classes\Api\Type
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractApiType
{
    use ExtendableTrait;
    use Singleton;

    const TYPE_ALIAS                   = '';
    const PERMISSION                   = PermissionContainer::PERMISSION_CODE_GUEST;
    const EVENT_EXTEND_PERMISSION_LIST = 'lovata.api.extend.permissions';
    const EVENT_EXTEND_ACCESS_LOGIC    = 'lovata.api.extend.access_logic';

    /**
     * @var array Behaviors implemented by this class.
     */
    public $implement;

    /** @var ObjectType */
    protected $obTypeObject;

    /** @var \Lovata\Buddies\Models\User|\RainLab\User\Models\User|null */
    protected $obClient = null;

    /** @var int|null */
    protected $iUserId = null;

    /** @var string $sDescription */
    protected $sDescription = '';

    /**
     * @throws \Exception
     */
    protected function init()
    {
        $this->sDescription    = $this->getDescription();
        $this->extendableConstruct();
        $this->initClient();
        $this->initClientPermissions();
        $this->extendFrontendTypeFactory();
    }

    /**
     * Return new object type
     * @return ObjectType
     * @throws SystemException
     */
    public function getTypeObject()
    {
        if (empty($this->obTypeObject)) {
            return $this->createType();
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
     * @throws SystemException
     * @return ObjectType|EnumType|InputObjectType|InterfaceType|UnionType
     */
    protected function createType()
    {
        if (!TypeList::isValidValue(static::TYPE)) {
            throw new SystemException(static::TYPE . ' is not valid type');
        }

        $TypeClass = '\\GraphQL\\Type\\Definition\\' . static::TYPE;
        $this->obTypeObject = new $TypeClass($this->getTypeConfig());

        return $this->obTypeObject;
    }

    /**
     * Init client
     * @return void
     */
    protected function initClient()
    {
        $this->obClient = UserHelper::instance()->getUser();
        $this->iUserId = UserHelper::instance()->getUserID();
    }

    /**
     * Checking client authorization if object type are not guest permissions
     * @return bool
     */
    protected function checkAuth(): bool
    {
        $bResult = true;

        if (static::PERMISSION !== PermissionContainer::PERMISSION_CODE_GUEST && empty($this->obClient)) {
            ApiDataResponse::instance()->setErrorMessage(
                ApiDataResponse::CODE_NOT_AUTHORIZED,
                Lang::get('lovata.toolbox::lang.message.client_not_logged_in'),
            );

            $bResult = false;
        }

        return $bResult;
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

        //Check if the resolve method permission code is in the client's permission list
        if (!Arr::get($arClientPermissionList, $sTypePermissions)) {
            ApiDataResponse::instance()->setErrorMessage(
                ApiDataResponse::CODE_NOT_AUTHORIZED,
                Lang::get('lovata.toolbox::lang.message.'.ApiDataResponse::CODE_NOT_AUTHORIZED),
            );

            return false;
        }

        $arEventData = [
            'permissions' => $sTypePermissions,
            'subject'     => $this->obClient,
            'object'      => $obObject,
            'action'      => $arActions
        ];

        //Extending the access check logic
        $mEventAccessLogic = Event::fire(self::EVENT_EXTEND_ACCESS_LOGIC, $arEventData, true);

        $bResult = (is_bool($mEventAccessLogic)) ? $mEventAccessLogic : true;

        return $bResult;
    }

    /**
     * Get type description
     * @return string
     */
    protected function getDescription(): string
    {
        return '';
    }

    /**
     * Get type config
     * @return array
     */
    abstract protected function getTypeConfig(): array;

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
     * Extend frontend type factory
     * @return void
     */
    protected function extendFrontendTypeFactory()
    {
        FrontendTypeFactory::instance()->addTypeClass([
            FileInterfaceType::class,
            FileType::class,
            FilterCollectionInputType::class,
            GetNearestElementCollectionInputType::class,
            ImageFileType::class,
            ResizeImageInputType::class,
            ResizeImageModeEnumType::class,
            PaginateInputType::class,
            PaginationInfoType::class,
        ]);
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
