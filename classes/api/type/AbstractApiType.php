<?php namespace Lovata\Toolbox\Classes\Api\Type;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ObjectType;

use October\Rain\Support\Traits\Singleton;

/**
 * Class AbstractApiType
 * @package Lovata\Toolbox\Classes\Api\Type
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractApiType
{
    use Singleton;

    const TYPE_ALIAS = '';
    const PERMISSION = [];
    const IS_INPUT_TYPE = false;

    /** @var ObjectType */
    protected $obTypeObject;

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
            'fields' => $this->getFieldList(),
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
