<?php namespace Lovata\Toolbox\Classes\Helper;

use Mail;
use Event;
use October\Rain\Support\Traits\Singleton;

use Lovata\Toolbox\Models\Settings;
use Lovata\Toolbox\Traits\Helpers\TraitInitActiveLang;

/**
 * Class SendMailHelper
 * @package Lovata\Toolbox\Classes\Helper
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SendMailHelper
{
    use Singleton;
    use TraitInitActiveLang;

    const EVENT_BEFORE_SEND_EMAIL = 'email.before.send';

    /** @var bool */
    protected $bUseQueue = false;

    /** @var string */
    protected $sQueueName;

    /** @var string */
    protected $sMailTemplate;

    /** @var array */
    protected $arMailData = [];

    /**
     * Send email
     * @param string $sMailTemplate
     * @param string $sEmailList
     * @param array  $arDefaultEmailData
     * @param string $sEmailDataEventName
     * @param bool   $bCheckActiveLang
     */
    public function send($sMailTemplate, $sEmailList, $arDefaultEmailData = [], $sEmailDataEventName = null, $bCheckActiveLang = false)
    {
        if (empty($sEmailList) || (!is_string($sEmailList) && !is_array($sEmailList))) {
            return;
        }

        //Get template name
        $this->sMailTemplate = $sMailTemplate;
        if ($bCheckActiveLang) {
            $this->sMailTemplate = $this->addActiveLangSuffix($this->sMailTemplate);
        }

        //Get template data
        $this->arMailData = $this->getMailData($sEmailDataEventName, $arDefaultEmailData);

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
     * Init settings
     */
    protected function init()
    {
        //Get queue settings
        $this->bUseQueue = Settings::getValue('queue_on');
        $this->sQueueName = Settings::getValue('queue_name');
    }

    /**
     * @param string $sEmail
     */
    protected function sendMail($sEmail)
    {
        if (empty($this->sMailTemplate) || empty($sEmail)) {
            return;
        }

        $arEventData = [$this->sMailTemplate, $sEmail, $this->arMailData];
        $arEventData = Event::fire(self::EVENT_BEFORE_SEND_EMAIL, $arEventData);

        $arAttachList = $this->getAttachList($arEventData);

        //Send restore mail
        if ($this->bUseQueue && empty($this->sQueueName)) {
            Mail::queue($this->sMailTemplate, $this->arMailData, function ($obMessage) use ($sEmail, $arAttachList) {
                $obMessage->to($sEmail);
                foreach ($arAttachList as $sFilePath) {
                    $obMessage->attach($sFilePath);
                }
            });
        } elseif ($this->bUseQueue && !empty($this->sQueueName)) {
            Mail::queueOn($this->sQueueName, $this->sMailTemplate, $this->arMailData, function ($obMessage) use ($sEmail, $arAttachList) {
                $obMessage->to($sEmail);
                foreach ($arAttachList as $sFilePath) {
                    $obMessage->attach($sFilePath);
                }
            });
        } else {
            Mail::send($this->sMailTemplate, $this->arMailData, function ($obMessage) use ($sEmail, $arAttachList) {
                $obMessage->to($sEmail);
                foreach ($arAttachList as $sFilePath) {
                    $obMessage->attach($sFilePath);
                }
            });
        }
    }

    /**
     * Get mail data
     * @param string $sEventName
     * @param array  $arResult
     * @return array
     */
    protected function getMailData($sEventName, $arResult = [])
    {
        if (empty($sEventName)) {
            return $arResult;
        }

        //Get addition data for template
        //Fire event
        $arAdditionData = Event::fire($sEventName, $arResult);
        if (empty($arAdditionData) || !is_array($arAdditionData)) {
            return $arResult;
        }

        foreach ($arAdditionData as $arData) {
            if (empty($arData) || !is_array($arData)) {
                continue;
            }

            $arResult = array_merge($arResult, $arData);
        }

        return $arResult;
    }

    /**
     * Get attach list.
     * @param array|null $arEventData
     * @return array
     */
    protected function getAttachList($arEventData) : array
    {
        $arAttachList = [];

        if (empty($arEventData) || !is_array($arEventData)) {
            return $arAttachList;
        }

        foreach ($arEventData as $arAttachData) {
            if (empty($arAttachData)) {
                continue;
            }

            foreach ($arAttachData as $sKey => $sValue) {
                $arAttachList[] = $sValue;
            }
        }

        $arAttachList = array_unique($arAttachList);

        foreach ($arAttachList as $iKey => $sFilePath) {
            if (!file_exists($sFilePath)) {
                array_forget($arAttachList, $iKey);
            }
        }

        return $arAttachList;
    }
}
