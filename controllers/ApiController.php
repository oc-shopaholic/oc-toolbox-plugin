<?php namespace Lovata\Toolbox\Controllers;

use Log;
use Auth;
use Illuminate\Support\Arr;

use Lovata\Toolbox\Classes\Api\Response\ApiDataResponse;
use Lovata\Toolbox\Classes\Api\Type\FrontendTypeFactory;
use Lovata\Toolbox\Classes\Api\QueryProcessor;

class ApiController
{
    protected $obRequest;
    protected $obApiResponse;

    public function __construct($obRequest)
    {
        $this->obRequest = $obRequest;
        $this->obApiResponse = new ApiDataResponse();
    }

    /**
     * Execute api request and return response as json string
     * @return \Illuminate\Http\JsonResponse
     */
    public function query()
    {
        $sQuery = $this->obRequest->get('query');

        //Execute query and get result object
        $obQueryProcessor = new QueryProcessor($sQuery, FrontendTypeFactory::class);
        $this->executeQuery($obQueryProcessor);

        return $this->obApiResponse->response();
    }

    /**
     * Execute query processor
     * @param QueryProcessor $obQueryProcessor
     */
    protected function executeQuery($obQueryProcessor)
    {
        $obResult = $obQueryProcessor->execute();
        $arResult = $obResult->toArray();

        if (!empty($obResult->errors)) {
            $arErrorList = $obResult->errors;
            $obFirstError = array_shift($arErrorList);
            $this->obApiResponse->setErrorMessage(
                ApiDataResponse::CODE_ERROR,
                env('APP_ENV') == 'production' ? Arr::get($arResult, 'errors.0.message') : $obFirstError->getMessage(),
                env('APP_ENV') == 'production' ? $obResult->toArray() : $obResult->errors
            );

            $obPrevious = $obFirstError->getPrevious();
            Log::error('Query: '.$obQueryProcessor->getQuery());
            Log::error('Error: '.$obFirstError->getMessage());
            if (!empty($obPrevious)) {
                Log::error('Line: '.$obPrevious->getLine().', File: '.$obPrevious->getFile().', Message: '.$obPrevious->getMessage());
            }
        } else {
            $this->obApiResponse->setData(Arr::get($arResult, 'data'));
        }
    }
}
