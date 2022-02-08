<?php namespace Lovata\Toolbox\Classes\Api\Collection;

use Lovata\Toolbox\Classes\Api\Item\CategoryItemType;
use Lovata\Toolbox\Classes\Api\Type\TypeFactory;

use Lovata\Shopaholic\Classes\Collection\CategoryCollection;
use GraphQL\Type\Definition\Type;

/**
 * Class CategoryCollectionType
 * @package Lovata\Toolbox\Classes\Api\Collection
 */
class CategoryCollectionType extends AbstractCollectionType
{
    const COLLECTION_CLASS = CategoryCollection::class;
    const TYPE_ALIAS = 'category_list';

    /** @var CategoryCollectionType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = parent::getFieldList();
        $arFieldList['list'] = Type::listOf(TypeFactory::instance()->get(CategoryItemType::TYPE_ALIAS));
        $arFieldList['item'] = TypeFactory::instance()->get(CategoryItemType::TYPE_ALIAS);
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
