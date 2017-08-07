<?php namespace Kharanenka\Helper;

/**
 * Generate universal result answer
 *
 * Class ResultStore
 * @package Kharanenka\Helper
 * @author Andrey Kharanenka, kharanenka@gmail.com
 *
 */

class ResultStore
{
    /** @var bool Status of result (true|false) */
    private $bStatus = true;

    /** @var mixed Data of result */
    private $obData;
    
    /** @var string Error message */
    private $sErrorMessage = null;

    /** @var string Error code */
    private $sErrorCode = null;

    /** @var ResultStore */
    private static $obThis =  null;

    private function __construct() {}

    /**
     * @return ResultStore
     */
    public static function getInstance()
    {
        if(empty(self::$obThis)) {
            self::$obThis = new ResultStore();
        }

        return self::$obThis;
    }

    /**
     * Set data value and status of result in true
     * @param mixed $obData
     * @return ResultStore
     */
    public function setTrue($obData = null)
    {
        $this->bStatus  = true;
        $this->obData = $obData;
        return $this;
    }

    /**
     * Set data value and status of result in false
     * @param mixed $obData
     * @return ResultStore
     */
    public function setFalse($obData = null)
    {
        $this->bStatus  = false;
        $this->obData = $obData;
        return $this;
    }

    /**
     * Set error message value
     * @param string $sMessage
     * @return ResultStore
     */
    public function setMessage($sMessage)
    {
        $this->bStatus  = false;
        $this->sErrorMessage = $sMessage;
        return $this;
    }
    
    /**
     * Set error code value
     * @param string $sCode
     * @return ResultStore
     */
    public function setCode($sCode) {

        $this->bStatus  = false;
        $this->sErrorCode = $sCode;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function status()
    {
        return $this->bStatus;
    }
    
    /**
     * @return string
     */
    public function message()
    {
        return $this->sErrorMessage;
    }
    /**
     * @return string
     */
    public function code()
    {
        return $this->sErrorCode;
    }

    /**
     * @return mixed
     */
    public function data()
    {
        return $this->obData;
    }

    /**
     * Get result array
     * @return array
     */
    public function get()
    {
        $arResult = [
            'status' => $this->status(),
            'data'   => $this->data(),
        ];
        
        if(!$this->status()) {
            $arResult['message'] = $this->message();
            $arResult['code'] = $this->code();
        }
        
        return $arResult;
    }

    /**
     * Generate result JSON string
     * @return string
     */
    public function getJSON()
    {
        return json_encode($this->get());
    }
}