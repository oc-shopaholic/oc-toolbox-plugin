<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class ComponentDataCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ComponentDataCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = '{{studly_model}}Data.php';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/components/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/component_data.stub';
}
