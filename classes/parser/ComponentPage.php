<?php namespace Lovata\Toolbox\Classes\Parser;

/**
 * Class ComponentPage
 * @package Lovata\Toolbox\Classes\Parser
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ComponentPage extends CommonFile
{
    /** @var string */
    protected $sFile = '{{studly_model}}Page.php';
    /** @var string */
    protected $sPathFolder = '/{{lower_author}}/{{lower_plugin}}/components/';
    /** @var string */
    protected $sPathTemplate = '/lovata/toolbox/classes/parser/templates/component_page.stub';
}
