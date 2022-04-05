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

    /** @var bool */
    protected $bUseQueue = false;

    /** @var string */
    protected $sQueueName;

    /** @var string */
    protected $sMailTemplate;

    /** @var array */
    protected $arMailData = [];

    /** @var \DateTimeInterface|\DateInterval|int */
    protected $mDelay;

    /**
     * Send email
     * @param string                               $sMailTemplate
     * @param string|array                         $mEmailList
     * @param array                                $arDefaultEmailData
     * @param string                               $sEmailDataEventName
     * @param bool                                 $bCheckActiveLang
     * @param \DateTimeInterface|\DateInterval|int $mDelay
     */
    public function send($sMailTemplate, $mEmailList, $arDefaultEmailData = [], $sEmailDataEventName = null, $bCheckActiveLang = false, $mDelay = null)
    {
        if (empty($mEmailList) || (!is_string($mEmailList) && !is_array($mEmailList))) {
            return;
        }

        $this->mDelay = $mDelay;

        //Get template name
        $this->sMailTemplate = $sMailTemplate;
        if ($bCheckActiveLang) {
            $this->sMailTemplate = $this->addActiveLangSuffix($this->sMailTemplate);
        }

        //Get template data
        $this->arMailData = $this->getMailData($sEmailDataEventName, $arDefaultEmailData);

        //Process email list
        if (is_string($mEmailList)) {
            $arEmailList = explode(',', $mEmailList);
        } else {
            $arEmailList = $mEmailList;
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

        //Send restore mail
        if ($this->bUseQueue && empty($this->sQueueName)) {
            if (!empty($this->mDelay)) {
                Mail::later($this->mDelay, $this->sMailTemplate, $this->arMailData, function ($obMessage) use ($sEmail) {
                    $obMessage->to($sEmail);
                });
            } else {
                Mail::queue($this->sMailTemplate, $this->arMailData, function ($obMessage) use ($sEmail) {
                    $obMessage->to($sEmail);
                });
            }
        } elseif ($this->bUseQueue && !empty($this->sQueueName)) {
            if (!empty($this->mDelay)) {
                Mail::laterOn($this->sQueueName, $this->mDelay, $this->sMailTemplate, $this->arMailData, function ($obMessage) use ($sEmail) {
                    $obMessage->to($sEmail);
                });
            } else {
                Mail::queueOn($this->sQueueName, $this->sMailTemplate, $this->arMailData, function ($obMessage) use ($sEmail) {
                    $obMessage->to($sEmail);
                });             
            }
        } else {
            Mail::send($this->sMailTemplate, $this->arMailData, function ($obMessage) use ($sEmail) {
                $obMessage->to($sEmail);
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
}
