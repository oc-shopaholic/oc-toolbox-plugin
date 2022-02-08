<?php namespace Lovata\Toolbox\Classes\Api\Type;

use Closure;

/**
 * Class TypeFactory
 * @package Lovata\Toolbox\Classes\Api\Type
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class TypeFactory
{
    use \October\Rain\Extension\ExtendableTrait;

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
     * Extend this object properties upon construction.
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
