<?php namespace Lovata\Toolbox\Components;

use Lovata\Toolbox\Traits\Helpers\TraitComponentNotFoundResponse;
use Cms\Classes\ComponentBase;

/**
 * Class ElementPage
 * @package Lovata\Toolbox\Components
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class ElementPage extends ComponentBase
{
    use TraitComponentNotFoundResponse;

    /** @var \Model */
    protected $obElement = null;

    /** @var  \Lovata\Toolbox\Classes\Item\ElementItem */
    protected $obElementItem = null;

    /** @var array Component property list */
    protected $arPropertyList = [];

    /**
     * @return array
     */
    public function defineProperties()
    {
        $this->arPropertyList = array_merge($this->arPropertyList, $this->getElementPageProperties());
        return $this->arPropertyList;
    }

    /**
     * Get element object
     * @return \Illuminate\Http\Response|null
     */
    public function onRun()
    {
        //Get element slug
        $sElementSlug = $this->property('slug');
        if(empty($sElementSlug)) {
            return $this->getErrorResponse();
        }

        //Get element by slug
        $obElement = $this->getElementObject($sElementSlug);
        if(empty($obElement)) {
            return $this->getErrorResponse();
        }

        $this->obElement = $obElement;

        //Get element item
        $this->obElementItem = $this->makeItem($obElement->id, $obElement);

        return null;
    }

    /**
     * Get element object by slug
     * @param string $sElementSlug
     * @return \Model
     */
    protected abstract function getElementObject($sElementSlug);

    /**
     * Male new element item
     * @param int $iElementID
     * @param \Model $obElement
     * @return \Lovata\Toolbox\Classes\Item\ElementItem
     */
    protected abstract function makeItem($iElementID, $obElement);

    /**
     * Get element item
     * @return \Lovata\Toolbox\Classes\Item\ElementItem
     */
    public function get()
    {
        return $this->obElementItem;
    }
}
