<?php namespace Lovata\Toolbox\Classes\Component;

use System\Classes\PluginManager;
use Cms\Classes\ComponentBase;
use Lovata\Toolbox\Models\Settings;
use Lovata\Toolbox\Traits\Helpers\TraitComponentNotFoundResponse;
use Lovata\Toolbox\Traits\Helpers\TraitInitActiveLang;

/**
 * Class ElementPage
 * @package Lovata\Toolbox\Classes\Component
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class ElementPage extends ComponentBase
{
    use TraitInitActiveLang;
    use TraitComponentNotFoundResponse;

    /** @var \Model */
    protected $obElement = null;

    /** @var  \Lovata\Toolbox\Classes\Item\ElementItem */
    protected $obElementItem = null;

    /** @var array Component property list */
    protected $arPropertyList = [];

    protected $bNeedSmartURLCheck = false;

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
     * @throws \Exception
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
        if ($this->bNeedSmartURLCheck && $this->property('smart_url_check') && !$this->smartUrlCheck()) {
            $this->obElement = null;
            $this->obElementItem = null;
        }
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

    /**
     * Checks trans value, if value is not form active lang, then return false
     * @param \Model $obElement
     * @param string $sElementSlug
     * @return bool
     */
    protected function checkTransSlug($obElement, $sElementSlug)
    {
        if (empty($obElement) || empty($sElementSlug)) {
            return false;
        }

        $bResult = $obElement->slug == $sElementSlug;

        return $bResult;
    }

    /**
     * Smart check URL with additional checking
     * @return bool
     */
    protected function smartUrlCheck()
    {
        if (empty($this->obElementItem)) {
            return false;
        }

        $sCurrentURL = $this->currentPageUrl();
        $sValidURL = $this->obElementItem->getPageUrl($this->page->id);
        $bResult = $sCurrentURL == $sValidURL;

        return $bResult;
    }

    /**
     * Return true, if slug is translatable
     * @return bool
     */
    protected function isSlugTranslatable()
    {
        return (bool) Settings::getValue('slug_is_translatable') && PluginManager::instance()->hasPlugin('RainLab.Translate');
    }
}
