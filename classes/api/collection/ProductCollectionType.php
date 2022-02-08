<?php namespace Lovata\Toolbox\Classes\Api\Collection;

use Lovata\Toolbox\Classes\Api\Item\ProductItemType;
use Lovata\Toolbox\Classes\Api\Type\TypeFactory;

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
        $arArgumentList['sort'] = Type::string();

        return $arArgumentList;
    }
}
