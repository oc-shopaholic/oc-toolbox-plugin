<?php namespace Lovata\Toolbox\Http\Controllers;

use App;
use Backend\Classes\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Log;

use Lovata\Toolbox\Classes\Api\Response\ApiDataResponse;
use Lovata\Toolbox\Classes\Api\Type\FrontendTypeFactory;
use Lovata\Toolbox\Classes\Api\QueryProcessor;

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
            $sMessage = App::isProduction() ? Arr::get($arResult, 'errors.0.message') : $obResult->errors[0]->getMessage();
            $arErrors = App::isProduction() ? $obResult->toArray() : $obResult->errors;
            ApiDataResponse::instance()->setErrorMessage(
                ApiDataResponse::CODE_ERROR,
                $sMessage,
                $arErrors
            );

            $obPrevious = $obResult->errors[0]->getPrevious();
            Log::error('Query: '.$obQueryProcessor->getQuery());
            Log::error('Error: '.$obResult->errors[0]->getMessage());
            if (!empty($obPrevious)) {
                Log::error(sprintf('Line: %d, File: %s, Message: %s', $obPrevious->getLine(), $obPrevious->getFile(), $obPrevious->getMessage()));
            }
        } else {
            ApiDataResponse::instance()->setData(Arr::get($arResult, 'data'));
        }

        return ApiDataResponse::instance()->response();
    }
}
