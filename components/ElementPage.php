<?php namespace Lovata\Toolbox\Components;

use Lang;
use Lovata\Toolbox\Models\ExampleModel;
use Response;
use Cms\Classes\ComponentBase;

/**
 * Class ElementPage
 * @package Lovata\Toolbox\Components
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ElementPage extends ComponentBase {

    /** @var ExampleModel */
    protected $obElement;

    /**
     * @return array
     */
    public function componentDetails() {
        return [
            'name'        => 'lovata.toolbox::lang.component.element_page',
            'description' => 'lovata.toolbox::lang.component.element_page_desc'
        ];
    }

    /**
     * @return array
     */
    public function defineProperties() {
        return [
            'error_404' => [
                'title'             => Lang::get('lovata.toolbox::lang.component.property_name_error_404'),
                'description'       => Lang::get('lovata.toolbox::lang.component.property_description_error_404'),
                'default'           => 'on',
                'type'              => 'dropdown',
                'options' => [
                    'on'        => Lang::get('lovata.toolbox::lang.component.property_value_on'),
                    'off'       => Lang::get('lovata.toolbox::lang.component.property_value_off'),
                ],
            ],
            'slug' => [
                'title'             => Lang::get('lovata.toolbox::lang.component.property_slug'),
                'type'              => 'string',
                'default'           => '{{ :slug }}',
            ],
        ];
    }

    /**
     * Get element object
     * @return \Illuminate\Http\Response|void
     */
    public function onRun() {

        $bDisplayError404 = $this->property('error_404') == 'on' ? true : false;

        //Get element slug
        $sElementSlug = $this->property('slug');
        if(empty($sElementSlug)) {

            if(!$bDisplayError404) {
                return;
            }

            return Response::make($this->controller->run('404')->getContent(), 404);
        }

        //Get element by slug
        /** @var ExampleModel $obElement */
        $obElement = ExampleModel::getBySlug($sElementSlug)->first();
        if(empty($obElement)) {

            if(!$bDisplayError404) {
                return;
            }

            return Response::make($this->controller->run('404')->getContent(), 404);
        }

        $this->obElement = $obElement;
        return;
    }

    /**
     * Get element data
     * @return array|null
     */
    public function get() {

        if(empty($this->obElement)) {
            return null;
        }

        return $this->obElement->getCacheData($this->obElement->id, $this->obElement);
    }
}
