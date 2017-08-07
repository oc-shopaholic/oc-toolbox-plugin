<?php namespace Kharanenka\Scope;

/**
 * Class CategoryBelongsTo
 * @package Kharanenka\Scope
 * @author Andrey Kharanenka, kharanenka@gmail.com
 * 
 * @property int $category_id
 * @method static $this getByCategory(int $iCategoryID)
 */
trait CategoryBelongsTo {
    
    /**
     * Get element by category ID
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @param string $sData
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeGetByCategory($obQuery, $sData) {

        if(!empty($sData)) {
            $obQuery->where('category_id', $sData);
        }

        return $obQuery;
    }
}