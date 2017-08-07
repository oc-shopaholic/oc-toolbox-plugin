<?php namespace Kharanenka\Scope;

/**
 * Class UserBelongsTo
 * @package Kharanenka\Scope
 * @author Andrey Kharanenka, kharanenka@gmail.com
 * 
 * @property int $user_id
 * @method static $this getByUser(int $iUserID)
 */
trait UserBelongsTo {
    
    /**
     * Get element by user ID
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @param string $sData
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeGetByUser($obQuery, $sData) {

        if(!empty($sData)) {
            $obQuery->where('user_id', $sData);
        }

        return $obQuery;
    }
}