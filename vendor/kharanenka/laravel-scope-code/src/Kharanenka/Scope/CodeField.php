<?php namespace Kharanenka\Scope;

/**
 * Class CodeField
 * @package Kharanenka\Scope
 * @author Andrey Kharanenka, kharanenka@gmail.com
 *
 * @property string $code
 * 
 * @method static $this getByCode(string $sData)
 * @method static $this likeByCode(string $sData)
 * @method static $this nullCode()
 * @method static $this notNullCode()
 */

trait CodeField {

    /**
     * Get element by code value
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @param string $sData
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeGetByCode($obQuery, $sData)
    {
        if(!empty($sData)) {
            $obQuery->where('code', $sData);
        }

        return $obQuery;
    }

    /**
     * Get element like code value
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @param string $sData
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeLikeByCode($obQuery, $sData)
    {
        if(!empty($sData)) {
            $obQuery->where('code', 'like', '%'.$sData.'%');
        }

        return $obQuery;
    }

    /**
     * Get element with empty code
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeNullCode($obQuery)
    {
        return $obQuery->whereNull('code');
    }

    /**
     * Get element with not empty code
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeNotNullCode($obQuery)
    {
        return $obQuery->whereNotNull('code');
    }
}