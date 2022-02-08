<?php namespace Lovata\Toolbox\Classes\Api\Item;

use Lovata\Toolbox\Classes\Api\Type\Custom\Type as CustomType;

use Lovata\Shopaholic\Classes\Item\CategoryItem;
use GraphQL\Type\Definition\Type;

/**
 * Class CategoryItemType
 * @package Lovata\Toolbox\Classes\Api\Item
 */
class CategoryItemType extends AbstractItemType
{
    const ITEM_CLASS = CategoryItem::class;
    const TYPE_ALIAS = 'category';

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
            'id'                    => Type::int(),
            'name'                  => Type::string(),
            'slug'                  => Type::string(),
            'code'                  => Type::string(),
            'nest_depth'            => Type::int(),
            'parent_id'             => Type::int(),
            'product_count'         => Type::int(),
            'preview_text'          => Type::string(),
            'preview_image'         => [
                'type'    => CustomType::array(),
                'resolve' => function ($obCategoryItem) {
                    return $this->getImage($obCategoryItem, 'preview_image');
                },
            ],
            'icon'                  => [
                'type'    => CustomType::array(),
                'resolve' => function ($obCategoryItem) {
                    return $this->getImage($obCategoryItem, 'icon');
                },
            ],
            'description'           => Type::string(),
            'images'                => [
                'type'    => CustomType::array(),
                'resolve' => function ($obCategoryItem) {
                    return $this->getImageList($obCategoryItem, 'images');
                },
            ],
            'updated_at'            => Type::string(),
            //            'parent' => '', ///
            'children_id_list'      => CustomType::array(),
            //            'children' => '', ///
            'inherit_property_set'  => Type::boolean(),
            'property_set_id'       => CustomType::array(),
            //            'property_set'      => '', ///
            'product_property_list' => CustomType::array(),
            //            'product_property'      => '', ///
            'product_property'      => CustomType::array(),
            'offer_property_list'   => CustomType::array(),
            //            'offer_property'      => '', ///
            //            'product_filter_property'      => '', ///
            //            'offer_filter_property'      => '', ///
            'category_vk_id'        => Type::int(),
        ];

        return $arFieldList;
    }
}
