<?php namespace Lovata\Toolbox\Classes\Component;

use Cms\Classes\ComponentBase;
use Lovata\Toolbox\Traits\Helpers\TraitComponentNotFoundResponse;

/**
 * Class ElementPage
 * @package Lovata\Toolbox\Classes\Component
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
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
        if (empty($sElementSlug) && !$this->property('slug_required')) {
            return null;
        }

        if (empty($this->obElement)) {
            return $this->getErrorResponse();
        }

        return null;
    }

    /**
     * Init plugin method
     */
    public function init()
    {
        //Get element slug
        $sElementSlug = $this->property('slug');
        if (empty($sElementSlug)) {
            return;
        }

        //Get element by slug
        $this->obElement = $this->getElementObject($sElementSlug);
        if (empty($this->obElement)) {
            return;
        }

        //Get element item
        $this->obElementItem = $this->makeItem($this->obElement->id, $this->obElement);
    }

    /**
     * Get element item
     * @return \Lovata\Toolbox\Classes\Item\ElementItem
     */
    public function get()
    {
        return $this->obElementItem;
    }

    /**
     * Get element object by slug
     * @param string $sElementSlug
     * @return \Model
     */
    abstract protected function getElementObject($sElementSlug);

    /**
     * Male new element item
     * @param int $iElementID
     * @param \Model $obElement
     * @return \Lovata\Toolbox\Classes\Item\ElementItem
     */
    abstract protected function makeItem($iElementID, $obElement);
}
