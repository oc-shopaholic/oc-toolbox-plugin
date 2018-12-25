<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class ModelColumnCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ModelColumnCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = 'columns.yaml';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/models/{{lower_model}}/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/columns.stub';
}
