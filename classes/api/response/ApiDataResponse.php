<?php namespace Lovata\Toolbox\Classes\Api\Response;

use Response;
use Kharanenka\Helper\Result;
use October\Rain\Support\Traits\Singleton;

/**
 * Class ApiDataResponse
 * @package Lovata\Toolbox\Classes\Api\Response
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ApiDataResponse extends AbstractApiResponse
{
    use Singleton;

    /**
     * Set response data
     * @param array $arResponseData
     */
    public function setData($arResponseData = [])
    {
        Result::setTrue($arResponseData);
    }

    /**
     * Get response data
     * @return array
     */
    public function getData()
    {
        $arResult = Result::data();

        return $arResult;
    }

    /**
     * Return response how json
     * @return \Illuminate\Http\JsonResponse
     */
    public function response($arHeaders = [])
    {
        if (Result::status()) {
            return Response::json(Result::get(), 200, $arHeaders);
        }

        $iErrorCode = Result::code();
        if (!in_array($iErrorCode, $this->arValidErrorCodeList)) {
            $iErrorCode = self::CODE_NOT_CORRECT_REQUEST;
        }

        return Response::json(Result::get(), $iErrorCode, $arHeaders);
    }
}
