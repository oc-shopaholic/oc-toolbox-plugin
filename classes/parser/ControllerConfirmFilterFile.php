<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ControllerConfirmFilterFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ControllerConfirmFilterFile extends CommonFile
{
    /** @var string */
    protected $sFile = '_config_filter.yaml';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/controllers/{{lower_controller}}/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/controller_config_filter.stub';
}
