<?php namespace Lovata\Toolbox\Classes\Component;

use Input;
use Cms\Classes\ComponentBase;

/**
 * Class SortingElementList
 * @package Lovata\Toolbox\Classes\Component
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class SortingElementList extends ComponentBase
{
    /** @var string Active sorting value */
    protected $sSorting;

    /**
     * Get available sorting array
     * @return array
     */
    protected abstract function getAvailableSorting();

    /**
     * Init start data
     */
    public function init()
    {
        $this->setActiveSorting();
        parent::init();
    }

    /**
     * Get active sorting
     * @return string
     */
    public function getSorting()
    {
        return $this->sSorting;
    }

    /**
     * Set active sorting
     */
    protected function setActiveSorting()
    {
        $this->sSorting = Input::get('sort');
        if (empty($this->sSorting)) {
            $this->sSorting = $this->property('sorting');
        }

        if (!in_array($this->sSorting, $this->getAvailableSorting())) {
            $this->sSorting = $this->property('sorting');
        }
    }
}
