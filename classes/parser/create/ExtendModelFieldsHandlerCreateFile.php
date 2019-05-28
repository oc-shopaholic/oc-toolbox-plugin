<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class ExtendModelFieldsHandlerCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ExtendModelFieldsHandlerCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = 'Extend{{studly_model}}FieldsHandler.php';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/classes/event/{{lower_model}}/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/extend_model_fields_handler.stub';
}
