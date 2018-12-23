<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class EventModelFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class EventModelFile extends CommonFile
{
    /** @var string */
    protected $sFile = '{{studly_model}}ModelHandler.php';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/classes/event/{{lower_model}}/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/event_model.stub';
}
