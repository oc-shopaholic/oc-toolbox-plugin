<?php namespace Lovata\Toolbox\Classes\Helper;

use October\Rain\Support\Traits\Singleton;
use Lovata\Toolbox\Models\Settings;

/**
 * Class PriceHelper
 * @package Lovata\Toolbox\Classes\Helper
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class PriceHelper
{
    use Singleton;

    /** @var int */
    protected $iDecimal = 2;

    /** @var string */
    protected $sDecPoint = '.';

    /** @var string */
    protected $sThousandsSep = ' ';

    /**
     * Apply custom format for price float value
     * @param float $fPrice
     * @return string
     */
    public static function format($fPrice)
    {
        $fPrice = (float) $fPrice;

        $obThis = self::instance();

        return number_format($fPrice, $obThis->iDecimal, $obThis->sDecPoint, $obThis->sThousandsSep);
    }

    /**
     * Convert price string to float value
     * @param string $sValue
     * @return float
     */
    public static function toFloat($sValue)
    {
        $sValue = str_replace(',', '.', $sValue);
        $fPrice = (float) preg_replace("/[^0-9\.]/", "", $sValue);

        return $fPrice;
    }

    /**
     * Round float price value
     * @param float $fPrice
     *
     * @return  float
     */
    public static function round($fPrice)
    {
        return round($fPrice, 2);
    }

    /**
     * PriceHelper constructor.
     */
    protected function init()
    {
        //Get options from settings
        $iDecimalValue = (int) Settings::getValue('decimals');
        if ($iDecimalValue >= 0) {
            $this->iDecimal = $iDecimalValue;
        }

        $sDecPointValue = Settings::getValue('dec_point');
        switch ($sDecPointValue) {
            case 'comma':
                $this->sDecPoint = ',';
                break;
            default:
                $this->sDecPoint = '.';
        }

        $sThousandsSepValue = Settings::getValue('thousands_sep');
        switch ($sThousandsSepValue) {
            case 'space':
                $this->sThousandsSep = ' ';
                break;
            default:
                $this->sThousandsSep = '';
        }
    }
}
