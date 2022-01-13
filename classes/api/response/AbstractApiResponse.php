<?php namespace Lovata\Toolbox\Classes\Api\Response;

use Lang;
use Kharanenka\Helper\Result;

/**
 * Class AbstractApiResponse
 * @package Lovata\Toolbox\Classes\Api\Response
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractApiResponse
{
    const CODE_NOT_CORRECT_REQUEST = 400;
    const CODE_NOT_AUTHORIZED = 401;
    const CODE_FORBIDDEN = 403;
    const CODE_NOT_FOUND = 404;
    const CODE_ERROR = 500;
    const CODE_MAINTENANCE_MODE = 503;
    const CODE_TIMEOUT = 504;
    const CODE_UNKNOWN_ERROR = 520;
    const CODE_NOT_CONNECTION = 523;

    protected $arAvailableErrorCodeList = [
        self::CODE_NOT_CORRECT_REQUEST,
        self::CODE_NOT_AUTHORIZED,
        self::CODE_FORBIDDEN,
        self::CODE_NOT_FOUND,
        self::CODE_ERROR,
        self::CODE_MAINTENANCE_MODE,
        self::CODE_NOT_CONNECTION,
    ];

    protected $arValidErrorCodeList = [
        self::CODE_NOT_CORRECT_REQUEST,
        self::CODE_NOT_AUTHORIZED,
        self::CODE_FORBIDDEN,
        self::CODE_NOT_FOUND,
        self::CODE_ERROR,
        self::CODE_MAINTENANCE_MODE,
        self::CODE_NOT_CONNECTION,
    ];

    /**
     * Return response object
     * @return mixed
     */
    abstract public function response();

    /**
     * Return response status
     * @return bool
     */
    public function status(): bool
    {
        return Result::status();
    }

    /**
     * Return response error code
     * @return int|null
     */
    public function code(): ?int
    {
        return Result::code();
    }

    /**
     * Return response error message
     * @return string|null
     */
    public function message(): ?string
    {
        return Result::message();
    }

    /**
     * Return response data
     * @return mixed
     */
    public function data()
    {
        return Result::data();
    }

    /**
     * Set error message in response
     * @param int   $iCode
     * @param array $arResponseData
     * @param array $arMessageData
     * @return $this
     */
    public function setError($iCode, $arResponseData = [], $arMessageData = [])
    {
        if (!$this->isErrorCodeAvailable($iCode)) {
            $iCode = self::CODE_UNKNOWN_ERROR;
        }

        $sMessage = Lang::get('message.'.$iCode, $arMessageData);
        Result::setFalse($arResponseData)->setMessage($sMessage)->setCode($iCode);

        return $this;
    }

    /**
     * Set error message in response
     * @param int    $iCode
     * @param string $sMessage
     * @param array  $arResponseData
     * @return $this
     */
    public function setErrorMessage($iCode, $sMessage, $arResponseData = [])
    {
        Result::setFalse($arResponseData)->setMessage($sMessage)->setCode($iCode);

        return $this;
    }

    /**
     * Check error code is available
     * @param int $iCode
     * @return bool
     */
    protected function isErrorCodeAvailable($iCode): bool
    {
        if (empty($iCode)) {
            return false;
        }

        $bResult = in_array($iCode, $this->arAvailableErrorCodeList);

        return $bResult;
    }
}
