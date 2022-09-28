<?php namespace Lovata\Toolbox\Traits\Api;

trait SubscribeApiEventsTrait
{
    /**
     * Subscribe to API event if API route is requested
     * @param string $sClassName
     * @return void
     */
    public function addApiEvent(string $sClassName)
    {
        if (!self::isApiRouteRequest()) {
            return;
        }

        \Event::subscribe($sClassName);
    }

    /**
     * Subscribe to API events if api route requested
     * @param array $arClassList
     * @return void
     */
    public function addApiEventList(array $arClassList)
    {
        if (!self::isApiRouteRequest()) {
            return;
        }

        foreach ($arClassList as $sClassName) {
            \Event::subscribe($sClassName);
        }
    }

    /**
     * Checks the request for an API route
     * @return bool
     */
    public static function isApiRouteRequest(): bool
    {
        return request()->is(config('lovata.toolbox::api_route_name'));
    }
}
