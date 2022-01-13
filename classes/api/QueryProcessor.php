<?php namespace Lovata\Toolbox\Classes\Api;

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Executor\ExecutionResult;

use Lovata\Toolbox\Classes\Api\Type\QueryType;
use Lovata\Toolbox\Classes\Api\Type\TypeFactory;

use Lovata\Toolbox\Classes\Api\Type\Custom\Type;

/**
 * Class QueryProcessor
 * @package Lovata\Toolbox\Classes\Api
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class QueryProcessor
{
    /** @var string */
    protected $sRequestQuery;

    /**
     * QueryProcessor constructor
     *
     * @param string $sQuery
     * @param string $sFactoryClass
     */
    public function __construct(string $sQuery, $sFactoryClass)
    {
        $this->sRequestQuery = $sQuery;
        TypeFactory::init($sFactoryClass);
    }

    /**
     * Execute query
     *
     * @return ExecutionResult
     */
    public function execute(): ExecutionResult
    {
        $obResult = GraphQL::executeQuery($this->getSchemaObject(), $this->sRequestQuery);

        return $obResult;
    }

    /**
     * Get request query string
     * @return string
     */
    public function getQuery()
    {
        return $this->sRequestQuery;
    }

    /**
     * Create schema object
     *
     * @return Schema
     */
    protected function getSchemaObject(): Schema
    {
        $obSchema = new Schema(
            [
                'query'      => QueryType::instance()->getTypeObject(),
                'typeLoader' => function ($sTypeName) {
                    return TypeFactory::instance()->get($sTypeName);
                },
                'types'      => Type::getTypeList(),
            ]
        );

        return $obSchema;
    }
}
