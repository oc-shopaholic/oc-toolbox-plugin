<?php namespace Lovata\Toolbox\Traits\Models;

use Site;

/**
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 *
 * @property array                                                             $site_list
 *
 * @property \October\Rain\Database\Collection|\System\Models\SiteDefinition[] $site
 * @method \October\Rain\Database\Relations\MorphToMany|\System\Models\SiteDefinition site()
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
        $this->site()->sync($arValue);
    }

    /**
     * @return array
     */
    protected function getSiteListAttribute(): array
    {
        return $this->site->pluck('id')->toArray();
    }
}
