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
     * PriceHelper constructor.
     */
    protected function init()
    {
        //Get options from settings
        $iDecimal = Settings::getValue('decimals');
        if ($iDecimal != null) {
            $this->iDecimal = (int) $iDecimal;
        }

        $sDecPoint = Settings::getValue('dec_point');
        switch ($sDecPoint) {
            case 'comma':
                $this->sDecPoint = ',';
                break;
            default:
                $this->sDecPoint = '.';
        }

        $sThousandsSep = Settings::getValue('thousands_sep');
        switch ($sThousandsSep) {
            case 'space':
                $this->sThousandsSep = ' ';
                break;
            default:
                $this->sThousandsSep = '';
        }
    }

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
}
