<?php namespace Lovata\Toolbox\Components;

use Lovata\Toolbox\Classes\ComponentTraitNotFoundResponse;
use Lovata\Toolbox\Models\ExampleModel;
use Cms\Classes\ComponentBase;

/**
 * Class ElementPage
 * @package Lovata\Toolbox\Components
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ElementPage extends ComponentBase
{
    use ComponentTraitNotFoundResponse;

    /** @var ExampleModel */
    protected $obElement;

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'lovata.toolbox::lang.component.element_page',
            'description' => 'lovata.toolbox::lang.component.element_page_desc'
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        $arProperties = $this->getElementPageProperties();
        return $arProperties;
    }

    /**
     * Get element object
     * @return \Illuminate\Http\Response|void
     */
    public function onRun()
    {
        $bDisplayError404 = $this->property('error_404') == 'on' ? true : false;

        //Get element slug
        $sElementSlug = $this->property('slug');
        if(empty($sElementSlug)) {
            return $this->getErrorResponse($bDisplayError404);
        }

        //Get element by slug
        /** @var ExampleModel $obElement */
        $obElement = ExampleModel::getBySlug($sElementSlug)->first();
        if(empty($obElement)) {
            return $this->getErrorResponse($bDisplayError404);
        }

        $this->obElement = $obElement;
        return;
    }

    /**
     * Get element data
     * @return array|null
     */
    public function get()
    {
        if(empty($this->obElement)) {
            return null;
        }

        return $this->obElement->getCacheData($this->obElement->id, $this->obElement);
    }
}
