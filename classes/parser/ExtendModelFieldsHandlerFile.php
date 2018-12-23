<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ExtendModelFieldsHandlerFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ExtendModelFieldsHandlerFile extends CommonFile
{
    /** @var string */
    protected $sFile = 'Extend{{studly_model}}FieldsHandler.php';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/classes/event/{{lower_model}}/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/extend_model_fields_handler.stub';
}
