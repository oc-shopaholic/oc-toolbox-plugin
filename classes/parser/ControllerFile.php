<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ControllerFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ControllerFile extends CommonFile
{
    /** @var string */
    protected $sFile = '{{studly_controller}}.php';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/controllers/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/controller.stub';
}
