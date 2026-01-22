<?php namespace Lovata\Toolbox\Classes\Api\Type;

use Closure;
use October\Rain\Extension\ExtendableTrait;

/**
 * Class TypeFactory
 * @package Lovata\Toolbox\Classes\Api\Type
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class TypeFactory
{
    use ExtendableTrait;

    /**
     * @var array Behaviors implemented by this class.
     */
    public $implement;

    /** @var string[] */
    protected $arTypeList = [];

    /** @var string[] */
    protected $arQueryTypeList = [];

    protected static $obFactory;

    /**
     * __construct
     * @throws \Exception
     */
    final protected function __construct()
    {
        $this->extendableConstruct();
        $this->initList();
    }

    /**
     * Init type classes
     */
    public static function init($sFactoryClass)
    {
        static::$obFactory = $sFactoryClass::instance();
    }

    /**
     * @return TypeFactory
     */
    public static function instance()
    {
        return static::$obFactory ?? static::$obFactory = new static;
    }

    /**
     * Get available type list
     * @return array
     */
    public function getList(): array
    {
        return $this->arQueryTypeList;
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
     * Init list
     * @return void
     */
    protected function initList()
    {
        foreach ($this->arTypeClassList as $sTypeClass) {
            $this->arTypeList[$sTypeClass::TYPE_ALIAS] = $sTypeClass;
        }

        foreach ($this->arQueryClassList as $sTypeClass) {
            $this->arTypeList[$sTypeClass::TYPE_ALIAS] = $sTypeClass;
            $this->arQueryTypeList[$sTypeClass::TYPE_ALIAS] = $sTypeClass;
        }
    }
}
