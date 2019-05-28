<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class ModelFieldCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ModelFieldCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = 'fields.yaml';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/models/{{lower_model}}/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/fields.stub';
}
