<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class ExtendModelColumnsHandlerCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ExtendModelColumnsHandlerCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = 'Extend{{studly_model}}ColumnsHandler.php';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/classes/event/{{lower_model}}/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/extend_model_columns_handler.stub';
}
