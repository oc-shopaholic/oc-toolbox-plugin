<?php namespace Lovata\Toolbox\Classes\Api\Type;

use Lovata\Toolbox\Classes\Api\Mutation\AbstractMutationType;

/**
 * Class QueryType
 * @package Lovata\Toolbox\Classes\Api\Type
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class QueryType extends AbstractApiType
{
    const TYPE_ALIAS = 'query';

    /** @var QueryType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arAvailableTypeList = TypeFactory::instance()->getList();
        $arFieldList = [];
        foreach ($arAvailableTypeList as $sTypeName => $sClassName) {
            $obTypeObject = TypeFactory::instance()->get($sTypeName);

            if (is_a($sClassName, AbstractMutationType::class, true)) {
                continue;
            }

            $arFieldList[$sClassName::TYPE_ALIAS] = [
                'type'    => $obTypeObject,
                'args'    => $sClassName::instance()->getArguments(),
                'resolve' => $sClassName::instance()->getResolveMethod(),
            ];
        }

        return $arFieldList;
    }
}
