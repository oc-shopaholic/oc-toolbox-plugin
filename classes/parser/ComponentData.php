<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ComponentData
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ComponentData extends CommonFile
{
    /** @var string */
    protected $sFile = '{{studly_model}}Data.php';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/components/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/component_data.stub';
}
