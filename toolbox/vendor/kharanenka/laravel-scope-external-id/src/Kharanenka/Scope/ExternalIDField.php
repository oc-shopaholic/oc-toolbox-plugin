<?php namespace Kharanenka\Scope;

/**
 * Class ExternalIDField
 * @package Kharanenka\Scope
 * @author Andrey Kharanenka, kharanenka@gmail.com
 *
 * @property string $external_id
 * 
 * @method static $this getByExternalID(string $sData)
 * @method static $this nullExternalID()
 * @method static $this notNullExternalID()
 */

trait ExternalIDField {

    /**
     * Get element by external_id value
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @param string $sData
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeGetByExternalID($obQuery, $sData)
    {
        if(!empty($sData)) {
            $obQuery->where('external_id', $sData);
        }

        return $obQuery;
    }

    /**
     * Get element with empty external_id
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeNullExternalID($obQuery)
    {
        return $obQuery->whereNull('external_id');
    }

    /**
     * Get element with not empty external_id
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeNotNullExternalID($obQuery)
    {
        return $obQuery->whereNotNull('external_id');
    }
}