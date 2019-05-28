<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class EventModelCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class EventModelCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = '{{studly_model}}ModelHandler.php';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/classes/event/{{lower_model}}/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/event_model.stub';
}
