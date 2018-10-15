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

    const PROPERTY_MODE = 'mode';
    const PROPERTY_FLASH_ON = 'flash_on';
    const PROPERTY_REDIRECT_ON = 'redirect_on';
    const PROPERTY_REDIRECT_PAGE = 'redirect_page';

    protected $sMode = null;

    /**
     * Init plugin method
     */
    public function init()
    {
        $this->sMode = $this->property('mode');
        if (empty($this->sMode)) {
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
        if (empty($sField)) {
            return null;
        }

        return Input::old($sField);
    }

    /**
     * Get all old form fields
     * @return array|string
     */
    public function getOldFormData()
    {
        return Input::old();
    }

    /**
     * Get error message
     * @return mixed
     */
    public function getErrorMessage()
    {
        $arResult = [
            'message' => Session::get('message'),
            'field'   => Session::get('data.field'),
        ];

        return $arResult;
    }

    /**
     * Get redirect page property list
     * @return array
     */
    abstract protected function getRedirectPageProperties();

    /**
     * Get component property "mode"
     * @return array
     */
    protected function getModeProperty()
    {
        $arResult = [
            self::PROPERTY_MODE        => [
                'title'   => 'lovata.toolbox::lang.component.property_mode',
                'type'    => 'dropdown',
                'options' => [
                    self::MODE_SUBMIT => Lang::get('lovata.toolbox::lang.component.mode_'.self::MODE_SUBMIT),
                    self::MODE_AJAX   => Lang::get('lovata.toolbox::lang.component.mode_'.self::MODE_AJAX),
                ],
            ],
            self::PROPERTY_FLASH_ON    => [
                'title' => 'lovata.toolbox::lang.component.property_flash_on',
                'type'  => 'checkbox',
            ],
            self::PROPERTY_REDIRECT_ON => [
                'title' => 'lovata.toolbox::lang.component.property_redirect_on',
                'type'  => 'checkbox',
            ],
        ];

        try {
            $arPageList = Page::getNameList();
        } catch (\Exception $obException) {
            $arPageList = [];
        }

        if (!empty($arPageList)) {
            $arResult[self::PROPERTY_REDIRECT_PAGE] = [
                'title'             => 'lovata.toolbox::lang.component.property_redirect_page',
                'type'              => 'dropdown',
                'options'           => $arPageList,
            ];
        }

        return $arResult;
    }

    /**
     * Get response (mode = form)
     * @param string $sRedirectURL
     * @return \Illuminate\Http\RedirectResponse|null
     */
    protected function getResponseModeForm($sRedirectURL = null)
    {
        if (!Result::status()) {
            return Redirect::back()->withInput()->with(Result::get());
        }

        $bRedirectOn = $this->property(self::PROPERTY_REDIRECT_ON);
        $sRedirectPage = $this->property(self::PROPERTY_REDIRECT_PAGE);
        if (!$bRedirectOn) {
            return null;
        }

        if (!empty($sRedirectURL)) {
            return Redirect::to($sRedirectURL);
        }

        if (empty($sRedirectPage)) {
            return Redirect::to('/');
        }

        $sRedirectURL = Page::url($sRedirectPage, $this->getRedirectPageProperties());

        return Redirect::to($sRedirectURL);
    }

    /**
     * Get response (mode = response)
     * @param string $sRedirectURL
     * @return \Illuminate\Http\RedirectResponse|array
     */
    protected function getResponseModeAjax($sRedirectURL = null)
    {
        $this->sendFlashMessage();

        if (!Result::status()) {
            return Result::get();
        }

        $bRedirectOn = $this->property(self::PROPERTY_REDIRECT_ON);
        $sRedirectPage = $this->property(self::PROPERTY_REDIRECT_PAGE);
        if (!$bRedirectOn) {
            return Result::get();
        }

        if (!empty($sRedirectURL)) {
            return Redirect::to($sRedirectURL);
        }

        if (empty($sRedirectPage)) {
            return Redirect::to('/');
        }

        $sRedirectURL = Page::url($sRedirectPage, $this->getRedirectPageProperties());

        return Redirect::to($sRedirectURL);
    }

    /**
     * Send flash message
     */
    protected function sendFlashMessage()
    {
        $bFlashOn = $this->property(self::PROPERTY_FLASH_ON);
        if (!$bFlashOn) {
            return;
        }

        $sMessage = Result::message();
        if (empty($sMessage)) {
            return;
        }

        if (Result::status()) {
            Flash::success($sMessage);
        } else {
            Flash::error($sMessage);
        }
    }
}
