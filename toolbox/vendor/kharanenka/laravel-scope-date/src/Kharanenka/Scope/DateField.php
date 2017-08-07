<?php namespace Kharanenka\Scope;

use Carbon\Carbon;

/**
 * Class DateField
 * @package Kharanenka\Scope
 * @author Andrey Kharanenka, kharanenka@gmail.com
 * 
 *  @method static $this getByDateValue(string $sFieldName, string $sDate, string $sCondition)
 */
trait DateField {

    /**
     * Get by date
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @param string $sDate
     * @param string $sField
     * @param string $sCondition
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeGetByDateValue($obQuery, $sField, $sDate, $sCondition = '=')
    {

        if(empty($sDate) || empty($sCondition) || empty($sField)) {
            return $obQuery;
        }

        return $obQuery->where($sField, $sCondition, $sDate);
    }
    
    /**
     * Get date value
     * @param string $sFieldName
     * @param string $sFormat
     * @return null|string
     */
    public function getDateValue($sFieldName, $sFormat = 'd.m.Y')
    {
        if(empty($sFieldName) || empty($sFormat)) {
            return null;
        }
        
        /** @var Carbon $obDate */
        $obDate = $this->$sFieldName;
        if(empty($obDate) || !$obDate instanceof Carbon) {
            return $obDate;
        }
        
        return $obDate->format($sFormat);
    }
}