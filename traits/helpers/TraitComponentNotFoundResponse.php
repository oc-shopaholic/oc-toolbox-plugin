<?php namespace Lovata\Toolbox\Traits\Helpers;

use Request;
use Response;
use October\Rain\Exception\AjaxException;

/**
 * Class TraitComponentNotFoundResponse
 * @package Lovata\Toolbox\Traits\Helpers
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
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
            'slug'          => [
                'title'   => 'lovata.toolbox::lang.component.property_slug',
                'type'    => 'string',
                'default' => '{{ :slug }}',
            ],
            'slug_required' => [
                'title'   => 'lovata.toolbox::lang.component.property_slug_required',
                'type'    => 'checkbox',
                'default' => 1,
            ],
        ];
    }

    /**
     * Get error response for 404 page
     * @throws AjaxException
     * @return \Illuminate\Http\Response
     */
    protected function getErrorResponse()
    {
        if (Request::ajax()) {
            throw new AjaxException('Element not found');
        }

        return Response::make($this->controller->run('404')->getContent(), 404);
    }
}
