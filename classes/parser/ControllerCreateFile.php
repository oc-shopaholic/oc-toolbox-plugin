<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ControllerCreateFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ControllerCreateFile extends CommonFile
{
    /** @var string */
    protected $sFile = 'create.htm';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/controllers/{{lower_controller}}/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/controller_create.stub';
}
