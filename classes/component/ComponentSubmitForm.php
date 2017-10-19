<?php namespace Lovata\Toolbox\Classes\Component;

use Flash;
use Lang;
use Input;
use Session;
use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Kharanenka\Helper\Result;

/**
 * Class ComponentSubmitForm
 * @package Lovata\Toolbox\Classes\Component
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class ComponentSubmitForm extends ComponentBase
{
    const MODE_SUBMIT = 'submit';
    const MODE_AJAX = 'ajax';

    protected $sMode = null;

    /**
     * Get redirect page property list
     * @return array
     */
    protected abstract function getRedirectPageProperties();

    /**
     * Init plugin method
     */
    public function init()
    {
        $this->sMode = $this->property('mode');
        if(empty($this->sMode)) {
            $this->sMode = self::MODE_AJAX;
        }
    }

    /**
     * Get old form data value
     * @param string $sField
     * @return mixed
     */
    public function getOldValue($sField)
    {
        if(empty($sField)) {
            return null;
        }

        return Input::old($sField);
    }

    /**
     * Get error message
     * @return mixed
     */
    public function getErrorMessage()
    {
        $arResult = [
            'message' => Session::get('message'),
            'field'   => Session::get('field'),
        ];

        return $arResult;
    }

    /**
     * Get component property "mode"
     * @return array
     */
    protected function getModeProperty()
    {
        $arResult = [
            'mode'        => [
                'title'   => 'lovata.toolbox::lang.component.property_mode',
                'type'    => 'dropdown',
                'options' => [
                    self::MODE_SUBMIT => Lang::get('lovata.toolbox::lang.component.mode_' . self::MODE_SUBMIT),
                    self::MODE_AJAX   => Lang::get('lovata.toolbox::lang.component.mode_' . self::MODE_AJAX),
                ],
            ],
            'flash_on'    => [
                'title' => 'lovata.toolbox::lang.component.property_flash_on',
                'type'  => 'checkbox',
            ],
            'redirect_on' => [
                'title' => 'lovata.toolbox::lang.component.property_redirect_on',
                'type'  => 'checkbox',
            ],
        ];

        try {
            $arPageList = Page::getNameList();
        } catch (\Exception $obException) {
            $arPageList = [];
        }

        if(!empty($arPageList)) {
            $arResult['redirect_page'] = [
                'title'             => 'lovata.toolbox::lang.component.property_redirect_page',
                'type'              => 'dropdown',
                'options'           => $arPageList,
            ];
        }

        return $arResult;
    }

    /**
     * Get response (mode = form)
     * @return \Illuminate\Http\RedirectResponse|null
     */
    protected function getResponseModeForm()
    {
        if(!Result::status()) {
            return Redirect::back()->withInput()->with(Result::get());
        }

        $bRedirectOn = $this->property('redirect_on');
        $sRedirectPage = $this->property('redirect_page');
        if(!$bRedirectOn) {
            return null;
        }

        if(empty($sRedirectPage)) {
            return Redirect::to('/');
        }

        $sRedirectURL = Page::url($sRedirectPage, $this->getRedirectPageProperties());
        return Redirect::to($sRedirectURL);
    }

    /**
     * Get response (mode = response)
     * @return \Illuminate\Http\RedirectResponse|array
     */
    protected function getResponseModeAjax()
    {
        $bFlashOn = $this->property('flash_on');
        if($bFlashOn) {
            $sMessage = Result::message();
            if(!empty($sMessage)) {
                Flash::error($sMessage);
            }
        }

        if(!Result::status()) {
            return Result::get();
        }

        $bRedirectOn = $this->property('redirect_on');
        $sRedirectPage = $this->property('redirect_page');
        if(!$bRedirectOn) {
            return Result::get();
        }

        if(empty($sRedirectPage)) {
            return Redirect::to('/');
        }

        $sRedirectURL = Page::url($sRedirectPage, $this->getRedirectPageProperties());
        return Redirect::to($sRedirectURL);
    }
}
