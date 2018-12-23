<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ExtendBackendMenuHandlerFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ExtendBackendMenuHandlerFile extends CommonFile
{
    /** @var string */
    protected $sFile = 'ExtendBackendMenuHandler.php';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/classes/event/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/extend_backend_menu_handler.stub';
}
