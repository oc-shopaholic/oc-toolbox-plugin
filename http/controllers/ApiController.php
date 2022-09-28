<?php namespace Lovata\Toolbox\Http\Controllers;

use Log;
use Response;
use Illuminate\Http\Request;
use Backend\Classes\Controller;

use GraphQL\Error\Error;
use Lovata\Toolbox\Classes\Api\Error\MethodNotFoundException;

use Lovata\Toolbox\Classes\Api\QueryProcessor;
use Lovata\Toolbox\Classes\Api\Type\FrontendTypeFactory;

/**
 * Class ApiController
 * @package Lovata\Toolbox\Http\Controllers
 * @return \Illuminate\Http\JsonResponse
 */
class ApiController extends Controller
{
    public function __invoke(Request $obRequest)
    {
        $sQuery = $obRequest->get('query');
        $arVariables = $obRequest->get('variables');

        //Execute query and get result object
        $obQueryProcessor = new QueryProcessor($sQuery, $arVariables, FrontendTypeFactory::class);
        $obResult = $obQueryProcessor->execute();
        $arResult = $obResult->toArray();

        if (!empty($obResult->errors)) {
            foreach ($obResult->errors as $obError) {
                //Exclude user errors from the logs (for example, an error in the field name)
                if (!in_array(
                    $obError->getCategory(),
                    [Error::CATEGORY_INTERNAL,
                    MethodNotFoundException::CATEGORY_BUSINESS_LOGIC]
                )) {
                    continue;
                }

                Log::error(
                    $obError->getMessage() . PHP_EOL . PHP_EOL
                    . 'Query:' . PHP_EOL
                    . $sQuery . PHP_EOL
                    . 'Exception in ' . $obError->getFile() . ':' . $obError->getLine() . PHP_EOL
                    . 'Stack trace:' . PHP_EOL
                    . $obError->getTraceAsString()
                );
            }

            return Response::json($arResult['errors']);
        }

        return Response::json($arResult);
    }
}
