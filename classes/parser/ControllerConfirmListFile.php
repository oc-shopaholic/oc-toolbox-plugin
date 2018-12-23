<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ControllerConfirmListFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ControllerConfirmListFile extends CommonFile
{
    /** @var string */
    protected $sFile = 'config_list.yaml';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/controllers/{{lower_controller}}/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/controller_config_list.stub';
}
