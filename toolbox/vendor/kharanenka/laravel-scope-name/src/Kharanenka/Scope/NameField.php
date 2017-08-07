<?php namespace Kharanenka\Scope;

/**
 * Class NameField
 * @package Kharanenka\Scope
 * @author Andrey Kharanenka, kharanenka@gmail.com
 *
 * @property string $name
 * 
 * @method static $this getByName(string $sData)
 * @method static $this likeByName(string $sData)
 * @method static $this nullName()
 * @method static $this notNullName()
 */

trait NameField {

    /**
     * Get element by name value
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @param string $sData
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeGetByName($obQuery, $sData)
    {
        if(!empty($sData)) {
            $obQuery->where('name', $sData);
        }

        return $obQuery;
    }

    /**
     * Get element like name value
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @param string $sData
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeLikeByName($obQuery, $sData)
    {
        if(!empty($sData)) {
            $obQuery->where('name', 'like', '%'.$sData.'%');
        }

        return $obQuery;
    }

    /**
     * Get element with empty name
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeNullName($obQuery)
    {
        return $obQuery->whereNull('name');
    }

    /**
     * Get element with not empty name
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeNotNullName($obQuery)
    {
        return $obQuery->whereNotNull('name');
    }
}