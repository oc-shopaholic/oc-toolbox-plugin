<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class ComponentListCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ComponentListCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = '{{studly_model}}List.php';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/components/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/component_list.stub';
}
