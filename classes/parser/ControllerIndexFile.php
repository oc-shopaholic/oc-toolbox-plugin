<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ControllerIndexFile
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ControllerIndexFile extends CommonFile
{
    /** @var string */
    protected $sFile = 'index.htm';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/controllers/{{lower_controller}}/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/controller_index.stub';
}
