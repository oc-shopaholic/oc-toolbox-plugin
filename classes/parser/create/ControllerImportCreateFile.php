<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class ControllerImportCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ControllerImportCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = 'import.htm';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/controllers/{{lower_controller}}/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/controller_import.stub';
}
