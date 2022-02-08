<?php namespace Lovata\Toolbox\Classes\Api\Item;

use Lovata\Toolbox\Classes\Api\Type\Custom\Type as CustomType;
use Lovata\Toolbox\Classes\Api\Item\AbstractItemType;

use Lovata\Shopaholic\Classes\Item\OfferItem;
use GraphQL\Type\Definition\Type;

/**
 * Class OfferItemType
 * @package Lovata\Toolbox\Classes\Api\Item
 */
class OfferItemType extends AbstractItemType
{
    const ITEM_CLASS = OfferItem::class;
    const TYPE_ALIAS = 'offer';
    const EVENT_EXTEND_TYPE_FIELDS = 'lovata.toolbox.api.extend.offer_item_type';

    /* @var OfferItemType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'id'                               => Type::int(),
            'active'                           => Type::boolean(),
            'trashed'                          => Type::boolean(),
            'name'                             => Type::string(),
            'code'                             => Type::string(),
            'product_id'                       => Type::int(),
//            //            'product'                   => '', ///
            'weight'                           => Type::float(),
            'height'                           => Type::float(),
            'length'                           => Type::float(),
            'width'                            => Type::float(),
            'quantity_in_unit'                 => Type::float(),
            'measure_id'                       => Type::int(),
//            //            'measure'                   => '', ///
            'measure_of_unit_id'               => Type::int(),
//            //            'measure_of_unit'           => '', ///
//            //            'dimensions_measure'        => '', ///
//            //            'weight_measure'            => '', ///
            'preview_text'                     => Type::string(),
            'preview_image' => [
                'type'    => CustomType::array(),
                'resolve' => function ($obOfferItem) {
                    return $this->getImage($obOfferItem, 'preview_image');
                },
            ],
            'description'                      => Type::string(),
            'images'        => [
                'type'    => CustomType::array(),
                'resolve' => function ($obOfferItem) {
                    return $this->getImageList($obOfferItem, 'images');
                },
            ],
            'price'                            => Type::string(),
            'price_value'                      => Type::float(),
//            'tax_price'                        => Type::string(),
//            'tax_price_value'                  => Type::float(),
//            'price_without_tax'                => Type::string(),
//            'price_without_tax_value'          => Type::float(),
//            'price_with_tax'                   => Type::string(),
//            'price_with_tax_value'             => Type::float(),
//            'old_price'                        => Type::string(),
//            'old_price_value'                  => Type::float(),
//            'tax_old_price'                    => Type::string(),
//            'tax_old_price_value'              => Type::float(),
//            'old_price_without_tax'            => Type::string(),
//            'old_price_without_tax_value'      => Type::float(),
//            'old_price_with_tax'               => Type::string(),
//            'old_price_with_tax_value'         => Type::float(),
//            'discount_price'                   => Type::string(),
//            'discount_price_value'             => Type::float(),
//            'tax_discount_price'               => Type::string(),
//            'tax_discount_price_value'         => Type::float(),
//            'discount_price_without_tax'       => Type::string(),
//            'discount_price_without_tax_value' => Type::float(),
//            'discount_price_with_tax'          => Type::string(),
//            'discount_price_with_tax_value'    => Type::float(),
//            'price_list'                       => CustomType::array(),
            'currency'                         => Type::string(),
            'currency_code'                    => Type::string(),
//            'tax_percent'                      => Type::float(),
//            //            'tax_list'        => Type::float(), ///
            'quantity'                         => Type::int(),
//            'property_value_array'             => CustomType::array(),
//            //            'property'          => CustomType::array(), ///
            'discount_id'                      => Type::int(),
            'discount_value'                   => Type::float(),
            'discount_type'                    => Type::string(),
            'subscription_period_id'           => Type::int(),
//            //                        'subscription_period'          => Type::int(), ///
//            //                        'downloadable_file'          => Type::int(), ///
        ];

        return $this->extendFieldList($arFieldList);
    }
}
