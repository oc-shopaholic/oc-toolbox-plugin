<?php namespace Lovata\Toolbox\Classes\Api;

use Event;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Executor\ExecutionResult;
use GraphQL\Validator\DocumentValidator;

use Lovata\Toolbox\Classes\Api\Type\MutationType;
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
    const EVENT_EXTEND_GLOBAL_VALIDATION_RULES = 'lovata.api.extend.globalQueryValidationRules';

    /** @var string */
    protected $sRequestQuery;
    /** @var array */
    protected $arRequestVariables;

    /**
     * QueryProcessor constructor
     *
     * @param string $sQuery
     * @param string $sFactoryClass
     */
    public function __construct(string $sQuery, array $arVariables, $sFactoryClass)
    {
        $this->addGlobalValidationRules();
        $this->sRequestQuery      = $sQuery;
        $this->arRequestVariables = $arVariables;
        TypeFactory::init($sFactoryClass);
    }

    /**
     * Execute query
     *
     * @return ExecutionResult
     */
    public function execute(): ExecutionResult
    {
        $obResult = GraphQL::executeQuery($this->getSchemaObject(), $this->sRequestQuery, null, null, $this->arRequestVariables);

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
                'mutation'   => MutationType::instance()->getTypeObject(),
                'typeLoader' => function ($sTypeName) {
                    return TypeFactory::instance()->get($sTypeName);
                },
                'types'      => Type::getTypeList(),
            ]
        );

        return $obSchema;
    }

    /**
     * Add global validation rules
     * @return void
     */
    protected function addGlobalValidationRules()
    {
        $arGlobalRules = Event::fire(self::EVENT_EXTEND_GLOBAL_VALIDATION_RULES);

        if (empty($arGlobalRules)) {
            return;
        }

        foreach ($arGlobalRules as $sRule) {
            DocumentValidator::addRule($sRule);
        }
    }
}
