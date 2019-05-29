<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class ExtendBackendMenuHandlerCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ExtendBackendMenuHandlerCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = 'ExtendBackendMenuHandler.php';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/classes/event/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/extend_backend_menu_handler.stub';
}
