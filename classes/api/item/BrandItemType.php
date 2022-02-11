<?php namespace Lovata\Toolbox\Classes\Api\Item;

use GraphQL\Type\Definition\Type;
use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Toolbox\Classes\Api\Type\Custom\Type as CustomType;

/**
 * Class BrandItemType
 * @package Lovata\Toolbox\Classes\Api\Item
 */
class BrandItemType extends AbstractItemType
{
    const ITEM_CLASS = BrandItem::class;
    const TYPE_ALIAS = 'brand';

    /* @var BrandItemType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'id'            => Type::int(),
            'active'        => Type::boolean(),
            'name'          => Type::string(),
            'slug'          => Type::string(),
            'code'          => Type::string(),
            'preview_text'  => Type::string(),
            'preview_image' => [
                'type'    => CustomType::array(),
                'resolve' => function ($obBrandItem) {
                    return $this->getImage($obBrandItem, 'preview_image');
                },
            ],
            'icon'          => [
                'type'    => CustomType::array(),
                'resolve' => function ($obBrandItem) {
                    return $this->getImage($obBrandItem, 'icon');
                },
            ],
            'images'        => [
                'type'    => CustomType::array(),
                'resolve' => function ($obBrandItem) {
                    return $this->getImageList($obBrandItem, 'images');
                },
            ],
            'description'   => Type::string(),
        ];

        return $arFieldList;
    }
}
