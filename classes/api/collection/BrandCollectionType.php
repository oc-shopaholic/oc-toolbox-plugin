<?php namespace Lovata\Toolbox\Classes\Api\Collection;

use Lovata\Toolbox\Classes\Api\Item\BrandItemType;
use Lovata\Toolbox\Classes\Api\Type\TypeFactory;

use Lovata\Shopaholic\Classes\Collection\BrandCollection;
use GraphQL\Type\Definition\Type;

/**
 * Class BrandCollectionType
 * @package Lovata\Toolbox\Classes\Api\Collection
 */
class BrandCollectionType extends AbstractCollectionType
{
    const COLLECTION_CLASS = BrandCollection::class;
    const TYPE_ALIAS = 'brand_list';

    /** @var BrandCollectionType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = parent::getFieldList();
        $arFieldList['list'] = Type::listOf(TypeFactory::instance()->get(BrandItemType::TYPE_ALIAS));
        $arFieldList['item'] = TypeFactory::instance()->get(BrandItemType::TYPE_ALIAS);
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
        $arArgumentList['category'] = Type::int();
        $arArgumentList['search'] = Type::string();

        return $arArgumentList;
    }
}
