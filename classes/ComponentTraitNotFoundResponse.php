<?php namespace Lovata\Toolbox\Classes;

use Lang;
use Response;

/**
 * Class ComponentTraitNotFoundResponse
 * @package Lovata\Toolbox\Classes
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 */
trait ComponentTraitNotFoundResponse {

    /**
     * Get component properties for element page
     * @return array
     */
    public function getElementPageProperties()
    {
        return [
            'error_404' => [
                'title'         => 'lovata.toolbox::lang.component.property_name_error_404',
                'description'   => 'lovata.toolbox::lang.component.property_description_error_404',
                'default'       => 'on',
                'type'          => 'dropdown',
                'options' => [
                    'on'    => Lang::get('lovata.toolbox::lang.component.property_value_on'),
                    'off'   => Lang::get('lovata.toolbox::lang.component.property_value_off'),
                ],
            ],
            'slug' => [
                'title'             => 'lovata.toolbox::lang.component.property_slug',
                'type'              => 'string',
                'default'           => '{{ :slug }}',
            ],
        ];
    }

    /**
     * Get error response for 404 page
     * @param bool $bDisplayError404
     * @return \Illuminate\Http\Response|void
     */
    protected function getErrorResponse($bDisplayError404)
    {
        if(!$bDisplayError404) {
            return;
        }

        return Response::make($this->controller->run('404')->getContent(), 404);
    }
}