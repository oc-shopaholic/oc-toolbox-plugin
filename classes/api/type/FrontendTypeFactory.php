<?php namespace Lovata\Toolbox\Classes\Api\Type;

use GraphQL\Error\Error;
use GraphQL\Type\Definition\ObjectType;

/** Collection types */
use Lovata\Toolbox\Classes\Api\Collection\BrandCollectionType;
use Lovata\Toolbox\Classes\Api\Collection\CategoryCollectionType;
use Lovata\Toolbox\Classes\Api\Collection\OfferCollectionType;
use Lovata\Toolbox\Classes\Api\Collection\ProductCollectionType;

/** Item types */
use Lovata\Toolbox\Classes\Api\Item\CategoryItemType;
use Lovata\Toolbox\Classes\Api\Item\OfferItemType;
use Lovata\Toolbox\Classes\Api\Item\ProductItemType;
use Lovata\Toolbox\Classes\Api\Item\BrandItemType;

/** Page types */

/** Mutation types */

/**
 * Class ContentTypeFactory
 * @package Lovata\Toolbox\Classes\Api\Type
 */
class FrontendTypeFactory extends TypeFactory
{
    /** @var string[] */
    protected $arTypeClassList = [
        /** Item types */
        ProductItemType::class,
        OfferItemType::class,
        CategoryItemType::class,
        BrandItemType::class,
        /** Collection types */
        ProductCollectionType::class,
        OfferCollectionType::class,
        CategoryCollectionType::class,
        BrandCollectionType::class,
    ];

    /** @var string[] */
    protected $arQueryClassList = [
        /** Item types */
        ProductItemType::class,
        OfferItemType::class,
        CategoryItemType::class,
        BrandItemType::class,
        /** Collection types */
        ProductCollectionType::class,
        OfferCollectionType::class,
        CategoryCollectionType::class,
        BrandCollectionType::class,
    ];

    /**
     * Get type object by class name
     * @param string $sTypeName
     * @return ObjectType
     * @throws \GraphQL\Error\Error
     */
    public function get(string $sTypeName)
    {
        $this->initList();

        if (!isset($this->arTypeList[$sTypeName])) {
            throw new Error("Type {$sTypeName} is not available");
        }

        $sClassName = $this->arTypeList[$sTypeName];

        return $sClassName::make()->getTypeObject();
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
     * Add type class
     * @param string|array $mClassName
     * @return void
     */
    public function addTypeClass($mClassName)
    {
        if (empty($mClassName)) {
            return;
        }

        $this->arTypeClassList = array_merge($this->arQueryClassList, self::toArray($mClassName));
    }

    /**
     * Add query class
     * @param string|array $mClassName
     * @return void
     */
    public function addQueryClass($mClassName)
    {
        if (empty($mClassName)) {
            return;
        }

        $this->arQueryClassList = array_merge($this->arQueryClassList, self::toArray($mClassName));
    }


    /**
     * Convert string to array
     * @param $mValue
     * @return array
     */
    public static function toArray($mValue): array
    {
        return (is_array($mValue)) ? $mValue : [$mValue];
    }
}
