<?php namespace Lovata\Toolbox\Classes\Api\Item;

use GraphQL\Type\Definition\Type;

use Lovata\Toolbox\Classes\Api\Type\Custom\Type as CustomType;
use Lovata\Shopaholic\Classes\Item\ProductItem;

/**
 * Class ProductItemType
 * @package Lovata\Toolbox\Classes\Api\Item
 */
class ProductItemType extends AbstractItemType
{
    const ITEM_CLASS = ProductItem::class;
    const TYPE_ALIAS = 'product';
    const EVENT_EXTEND_TYPE_FIELDS = 'lovata.toolbox.api.extend.product_item_type';

    /* @var ProductItemType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'id'                     => Type::int(),
            'active'                 => Type::boolean(),
            'trashed'                => Type::boolean(),
            'name'                   => Type::string(),
            'slug'                   => Type::string(),
            'code'                   => Type::string(),
            'category_id'            => Type::int(),
            'additional_category_id' => CustomType::array(),
            'additional_category'    => [
                'type'    => Type::listOf($this->getRelationType(CategoryItemType::TYPE_ALIAS)),
                'resolve' => function ($obProductItem) {
                    /* @var ProductItem $obProductItem */
                    return $obProductItem->additional_category;
                },
            ],
            'brand_id'               => Type::int(),
            'brand'                  => [
                'type'    => $this->getRelationType(BrandItemType::TYPE_ALIAS),
                'resolve' => function ($obProductItem) {
                    /* @var ProductItem $obProductItem */
                    return $obProductItem->brand;
                },
            ],
            'preview_text'           => Type::string(),
            'preview_image'          => [
                'type'    => CustomType::array(),
                'resolve' => function ($obProductItem) {
                    return $this->getImage($obProductItem, 'preview_image');
                },
            ],
            'description'            => Type::string(),
            'images'                 => [
                'type'    => CustomType::array(),
                'resolve' => function ($obProductItem) {
                    return $this->getImageList($obProductItem, 'images');
                },
            ],
            'offer_id_list'          => CustomType::array(),
            'offer'                  => [
                'type'    => Type::listOf($this->getRelationType(OfferItemType::TYPE_ALIAS)),
                'resolve' => function ($obProductItem) {
                    /* @var ProductItem $obProductItem */
                    return $obProductItem->offer;
                },
            ],
            'category'               => [
                'type'    => $this->getRelationType(CategoryItemType::TYPE_ALIAS),
                'resolve' => function ($obProductItem) {
                    /* @var ProductItem $obProductItem */
                    return $obProductItem->category;
                },
            ],
            'property_value_array'   => CustomType::array(),
//            'property'               => '', //
            'rating'                 => Type::boolean(),
            'rating_data'            => CustomType::array(),
//            'review'                 => '', //
//            'related'                => '', //
//            'accessory'              => '', //
//            'label'                  => '', //
            'is_file_access'         => Type::boolean(),
        ];

        return $this->extendFieldList($arFieldList);
    }
}
