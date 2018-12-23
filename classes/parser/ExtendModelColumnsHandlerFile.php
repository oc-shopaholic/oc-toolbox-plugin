<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ExtendModelColumnsHandlerFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ExtendModelColumnsHandlerFile extends CommonFile
{
    /** @var string */
    protected $sFile = 'Extend{{studly_model}}ColumnsHandler.php';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/classes/event/{{lower_model}}/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/extend_model_columns_handler.stub';
}
