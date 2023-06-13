<?php namespace Lovata\Toolbox\Traits\Models;

use Site;

/**
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 *
 * @property array $site_list
 *
 * @method static $this getBySite($iSiteID = null)
 */
trait MultisiteHelperTrait
{
    /**
     * @return array
     */
    public function getSiteListOptions(): array
    {
        /** @var \October\Rain\Database\Collection $obSiteList */
        $obSiteList = Site::listSites();
        if (empty($obSiteList) || $obSiteList->isEmpty()) {
            return [];
        }

        return $obSiteList->pluck('name', 'id')->toArray();
    }

    /**
     * @param array|null $arValue
     * @return void
     * @throws \Exception
     */
    protected function setSiteListAttribute($arValue)
    {
        $arValue = empty($arValue) ? [] : $arValue;
        $this->attributes['site_list'] = json_encode($arValue);
    }

    /**
     * Get not active elements
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @param int|null                                                             $iSiteID
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeGetBySite($obQuery, $iSiteID = null)
    {
        $iSiteID = empty($iSiteID) ? Site::getSiteIdFromContext() : $iSiteID;

        return $obQuery->where(function ($obQuery) use ($iSiteID) {
            if (!empty($iSiteID)) {
                $obQuery->whereJsonContains('site_list', (string) $iSiteID);
            }

            /** @var \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery */
            return $obQuery->orWhereNull('site_list')->orWhereJsonLength('site_list', 0);
        });
    }
}
