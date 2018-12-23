<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ComponentList
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ComponentList extends CommonFile
{
    /** @var string */
    protected $sFile = '{{studly_model}}List.php';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/components/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/component_list.stub';
}
