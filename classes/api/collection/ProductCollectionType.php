<?php namespace Lovata\Toolbox\Classes\Api\Collection;

use Lovata\Toolbox\Classes\Api\Item\ProductItemType;
use Lovata\Toolbox\Classes\Api\Type\TypeFactory;
use Lovata\Toolbox\Classes\Api\Type\Custom\Type as CustomType;

use Lovata\Shopaholic\Classes\Collection\ProductCollection;
use GraphQL\Type\Definition\Type;

/**
 * Class ProductCollectionType
 * @package Lovata\Toolbox\Classes\Api\Collection
 */
class ProductCollectionType extends AbstractCollectionType
{
    const COLLECTION_CLASS = ProductCollection::class;
    const TYPE_ALIAS = 'product_list';

    /** @var ProductCollectionType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = parent::getFieldList();
        $arFieldList['list'] = Type::listOf(TypeFactory::instance()->get(ProductItemType::TYPE_ALIAS));
        $arFieldList['item'] = TypeFactory::instance()->get(ProductItemType::TYPE_ALIAS);
        $arFieldList['id'] = Type::int();

        return $arFieldList;
    }

    /**
     * Get config for "args" attribute
     * @return array|null
     */
    protected function getArguments(): ?array
    {
        $arArgumentList = parent::getArguments();
        $arArgumentList['brand'] = Type::int();
        $arArgumentList['campaign'] = Type::int();
        $arArgumentList['categoryList'] = CustomType::array();
        $arArgumentList['categoryWithChildren'] = Type::boolean();
        $arArgumentList['couponGroup'] = Type::int();
        $arArgumentList['discount'] = Type::int();
        $arArgumentList['filterByBrandList'] = CustomType::array();
//        $arArgumentList['filterByPrice'] = CustomType::array(); // TODO: ($fStartPrice, $fStopPrice, [$iPriceTypeID = null])
//        $arArgumentList['filterByProperty'] = CustomType::array(); // TODO: ($arFilterList, $obPropertyList, [$obOfferList = null])
        $arArgumentList['getOfferMaxPrice'] = Type::string();
        $arArgumentList['getOfferMinPrice'] = Type::string();
        $arArgumentList['label'] = Type::int();
        $arArgumentList['promo'] = Type::int();
        $arArgumentList['promoBlock'] = Type::int();
        $arArgumentList['search'] = Type::string();
        $arArgumentList['sort'] = Type::string();
        $arArgumentList['tag'] = Type::int();

        return $arArgumentList;
    }

    protected function getCategoryParam($arArgumentList) {
        $arResult = array_get($arArgumentList, 'categoryList');
        $bWithChildren = (boolean) array_get($arArgumentList, 'categoryWithChildren');

        if ($bWithChildren) {
            $arResult[] = true;
        }


        return $arResult;
    }
}
