<?php namespace Lovata\Toolbox\Classes\Helper;

use Lovata\Toolbox\Traits\Helpers\TraitInitActiveLang;
use Mail;
use Event;
use October\Rain\Support\Traits\Singleton;

use Lovata\Toolbox\Models\Settings;

/**
 * Class SendMailHelper
 * @package Lovata\Toolbox\Classes\Helper
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SendMailHelper
{
    use Singleton;
    use TraitInitActiveLang;

    /** @var bool */
    protected $bUseQueue = false;

    /** @var string */
    protected $sQueueName;

    /** @var string */
    protected $sMailTemplate;

    /** @var array */
    protected $arMailData = [];

    /**
     * Init settings
     */
    protected function init()
    {
        //Get queue settings
        $this->bUseQueue = Settings::getValue('queue_on');
        $this->sQueueName = Settings::getValue('queue_name');
    }

    /**
     * Send email
     * @param string $sMailTemplate
     * @param string $sEmailList
     * @param array  $arDefaultEmailData
     * @param array  $arEmailData
     * @param string $sTemplateEventName
     * @param string $sEmailDataEventName
     * @param bool   $bCheckActiveLang
     */
    public function send($sMailTemplate, $sEmailList, $arDefaultEmailData = [], $arEmailData = [], $sTemplateEventName = null, $sEmailDataEventName = null, $bCheckActiveLang = false)
    {
        if (empty($sEmailList) || (!is_string($sEmailList) && !is_array($sEmailList))) {
            return;
        }

        //Get template name
        $this->sMailTemplate = $this->getMailTemplateName($sMailTemplate, $sTemplateEventName);
        if ($bCheckActiveLang) {
            $this->sMailTemplate = $this->addActiveLangSuffix($this->sMailTemplate);
        }

        //Get template data
        $this->arMailData = $this->getMailData($sEmailDataEventName, $arDefaultEmailData, $arEmailData);

        //Process email list
        if (is_string($sEmailList)) {
            $arEmailList = explode(',', $sEmailList);
        } else {
            $arEmailList = $sEmailList;
        }

        foreach ($arEmailList as $sEmail) {
            $sEmail = trim($sEmail);

            $this->sendMail($sEmail);
        }
    }

    /**
     * @param string $sEmail
     */
    protected function sendMail($sEmail)
    {
        if (empty($this->sMailTemplate) || empty($sEmail)) {
            return;
        }

        //Send restore mail
        if ($this->bUseQueue && empty($this->sQueueName)) {
            Mail::queue($this->sMailTemplate, $this->arMailData, function ($obMessage) use ($sEmail) {
                $obMessage->to($sEmail);
            });
        } elseif ($this->bUseQueue && !empty($this->sQueueName)) {
            Mail::queueOn($this->sQueueName, $this->sMailTemplate, $this->arMailData, function ($obMessage) use ($sEmail) {
                $obMessage->to($sEmail);
            });
        } else {
            Mail::send($this->sMailTemplate, $this->arMailData, function ($obMessage) use ($sEmail) {
                $obMessage->to($sEmail);
            });
        }
    }

    /**
     * Get mail data
     * @param string $sEventName
     * @param array  $arDefaultResult
     * @param array  $arEmailData
     * @return array
     */
    protected function getMailData($sEventName, $arDefaultResult = [], $arEmailData = [])
    {
        if (empty($sEventName) || !is_array($arEmailData)) {
            return $arDefaultResult;
        }

        $arEventData = $arDefaultResult;
        $arEventData['data'] = $arDefaultResult;

        //Get addition data for template
        //Fire event
        $arAdditionData = Event::fire($sEventName, $arEventData);
        if (empty($arAdditionData) || !is_array($arAdditionData)) {
            return $arDefaultResult;
        }

        $arResult = $arDefaultResult;
        foreach ($arAdditionData as $arData) {
            if (empty($arData) || !is_array($arData)) {
                continue;
            }

            $arResult = array_merge($arResult, $arData);
        }

        return $arResult;
    }

    /**
     * Get mail template name
     * @param string $sDefaultTemplateName
     * @param string $sEventName
     * @return string
     */
    protected function getMailTemplateName($sDefaultTemplateName, $sEventName = null)
    {
        if (empty($sEventName)) {
            return $sDefaultTemplateName;
        }

        //Fire event
        $sMailTemplate = Event::fire($sEventName);
        if (!empty($sMailTemplate) && is_array($sMailTemplate)) {
            $sMailTemplate = array_shift($sMailTemplate);
        }

        //Check template name value
        if (empty($sMailTemplate) || !is_string($sMailTemplate)) {
            return $sDefaultTemplateName;
        }

        return $sMailTemplate;
    }
}
