<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ControllerConfigReorder
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ControllerConfigReorder extends CommonFile
{
    /** @var string */
    protected $sFile = 'config_reorder.yaml';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/controllers/{{lower_controller}}/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/controller_config_reorder.stub';
}
