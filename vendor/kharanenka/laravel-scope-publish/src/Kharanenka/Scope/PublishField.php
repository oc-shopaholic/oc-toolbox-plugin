<?php namespace Kharanenka\Scope;

use Carbon\Carbon;

/**
 * Class PublishField
 * @package Kharanenka\Scope
 * @author Andrey Kharanenka, kharanenka@gmail.com
 * 
 * @property boolean $published
 * @property string $published_start
 * @property string $published_stop
 * 
 * @method static $this published()
 * @method static $this getPublished()
 * @method static $this getByPublishedStart(string $sDate, string $sCondition)
 * @method static $this getByPublishedStop(string $sDate, string $sCondition)
 */
trait PublishField {
    
    /**
     * Get published elements
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopePublished($obQuery) {
        return $obQuery->where('published', true);
    }

    /**
     * Get published elements
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeGetPublished($obQuery) {
        
        $sDateNow = Carbon::now()->format('Y-m-d H:i:s');
        return $obQuery->published()
            ->getByPublishedStart($sDateNow, '<=')
            ->where(function($obQuery) use ($sDateNow) {
                /** Builder|PublishedField */
                $obQuery->whereNull('published_stop')->orWhere('published_stop', '>', $sDateNow);
            });
    }

    /**
     * Get by published start
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @param string $sDate
     * @param string $sCondition
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeGetByPublishedStart($obQuery, $sDate, $sCondition = '=') {
        
        if(empty($sDate) || empty($sCondition)) {
            return $obQuery;
        }
        
        return $this->filterByDateValue('published_start', $obQuery, $sDate, $sCondition);
    }

    /**
     * Get by published stop
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @param string $sDate
     * @param string $sCondition
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeGetByPublishedStop($obQuery, $sDate, $sCondition = '=') {

        if(empty($sDate) || empty($sCondition)) {
            return $obQuery;
        }

        return $this->filterByDateValue('published_stop', $obQuery, $sDate, $sCondition);
    }

    /**
     * Get by date
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @param string $sDate
     * @param string $sField
     * @param string $sCondition
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    protected function filterByDateValue($sField, $obQuery, $sDate, $sCondition = '=')
    {

        if(empty($sDate) || empty($sCondition) || empty($sField)) {
            return $obQuery;
        }

        return $obQuery->where($sField, $sCondition, $sDate);
    }
}