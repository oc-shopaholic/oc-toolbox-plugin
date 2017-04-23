<?php namespace Lovata\Toolbox\Traits\Helpers;

use Response;

/**
 * Class TraitComponentNotFoundResponse
 * @package Lovata\Toolbox\Traits\Helpers
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 */
trait TraitComponentNotFoundResponse
{
    /**
     * Get component properties for element page
     * @return array
     */
    public function getElementPageProperties()
    {
        return [
            'slug' => [
                'title'             => 'lovata.toolbox::lang.component.property_slug',
                'type'              => 'string',
                'default'           => '{{ :slug }}',
            ],
        ];
    }

    /**
     * Get error response for 404 page
     * @return \Illuminate\Http\Response
     */
    protected function getErrorResponse()
    {
        return Response::make($this->controller->run('404')->getContent(), 404);
    }
}