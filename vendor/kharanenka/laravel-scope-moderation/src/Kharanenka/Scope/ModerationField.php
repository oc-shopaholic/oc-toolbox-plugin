<?php namespace Kharanenka\Scope;

/**
 * Class ModerationField
 * @package Kharanenka\Scope
 * @author Andrey Kharanenka, kharanenka@gmail.com
 *
 * @property bool $moderation
 * 
 * @method static $this moderation()
 * @method static $this notModeration()
 */

trait ModerationField {

    /**
     * Get moderation elements
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeModeration($obQuery) {
        return $obQuery->where('moderation', true);
    }

    /**
     * Get not moderation elements
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeNotModeration($obQuery) {
        return $obQuery->where('moderation', false);
    }
}