<?php namespace Lovata\Toolbox\Classes\Api\Type;

use Closure;
use Event;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ObjectType;

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

    const TYPE_ALIAS = '';
    const PERMISSION = [];
    const IS_INPUT_TYPE = false;
    const EVENT_EXTEND_FIELD_LIST = 'lovata.api.extend.fields';

    /**
     * @var array Behaviors implemented by this class.
     */
    public $implement;

    /** @var ObjectType */
    protected $obTypeObject;

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
