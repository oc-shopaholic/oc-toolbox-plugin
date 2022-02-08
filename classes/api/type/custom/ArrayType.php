<?php namespace Lovata\Toolbox\Classes\Api\Type\Custom;

use GraphQL\Language\AST\BooleanValueNode;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Language\AST\FloatValueNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Language\AST\NullValueNode;
use GraphQL\Language\AST\IntValueNode;
use GraphQL\Language\AST\Node;
use GraphQL\Error\Error;
use Exception;
use Illuminate\Support\Arr;

/**
 * Class ArrayType
 * @package Lovata\Toolbox\Classes\Api\Type\Custom
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ArrayType extends ScalarType
{
    /**
     * Serializes an internal value to include in a response
     *
     * @param mixed $value
     *
     * @return mixed
     *
     * @throws Error
     */
    public function serialize($value)
    {
        return (array) $value;
    }

    /**
     * Parses an externally provided value (query variable) to use as an input
     *
     * In the case of an invalid value this method must throw an Exception
     *
     * @param mixed $value
     *
     * @return mixed
     *
     * @throws Error
     */
    public function parseValue($value)
    {
        return (array) $value;
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input
     *
     * In the case of an invalid node or value this method must throw an Exception
     *
     * @param IntValueNode|FloatValueNode|StringValueNode|BooleanValueNode|NullValueNode $obValueNode
     * @param mixed[]|null                                                               $variables
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function parseLiteral(Node $obValueNode, ?array $variables = null)
    {
        $arResult = [];
        $arValueNode = $obValueNode->toArray();
        if (empty($arValueNode)) {
            return $arResult;
        }

        $arValueNodeValues = Arr::get($arValueNode, 'values');
        if (count($arValueNodeValues)) {
            /* @var Node $obNode */
            foreach ($arValueNodeValues as $iKey => $obNode) {
                if (empty($obNode->fields)) {
                    $arData = json_decode($obNode->value, true);
                    if (json_last_error()) {
                        $arResult[] = $obNode->value;
                    } else {
                        $arResult[] = $arData;
                    }
                } else {
                    /* @var Node $obNodeField */
                    foreach ($obNode->fields as $obNodeField) {
                        $arFiled = $obNodeField->toArray(1);
                        $arResult[$iKey][Arr::get($arFiled, 'name.value')] = Arr::get($arFiled, 'value.value');
                    }
                }
            }
        }

        return $arResult;
    }
}
