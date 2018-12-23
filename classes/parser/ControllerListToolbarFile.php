<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ControllerListToolbarFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ControllerListToolbarFile extends CommonFile
{
    /** @var string */
    protected $sFile = '_list_toolbar.htm';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/controllers/{{lower_controller}}/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/controller_list_toolbar.stub';
}
