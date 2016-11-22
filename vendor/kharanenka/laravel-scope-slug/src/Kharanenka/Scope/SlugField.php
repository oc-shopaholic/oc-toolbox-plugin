<?php namespace Kharanenka\Scope;

/**
 * Class SlugField
 * @package Kharanenka\Scope
 * @author Andrey Kharanenka, kharanenka@gmail.com
 *
 * @property string $slug
 * 
 * @method static $this getBySlug(string $sData)
 * @method static $this nullSlug()
 * @method static $this notNullSlug()
 */

trait SlugField {

    /**
     * Get element by slug value
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @param string $sData
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeGetBySlug($obQuery, $sData)
    {
        if(!empty($sData)) {
            $obQuery->where('slug', $sData);
        }

        return $obQuery;
    }

    /**
     * Get element with empty slug
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeNullSlug($obQuery)
    {
        return $obQuery->whereNull('slug');
    }

    /**
     * Get element with not empty slug
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeNotNullSlug($obQuery)
    {
        return $obQuery->whereNotNull('slug');
    }
}