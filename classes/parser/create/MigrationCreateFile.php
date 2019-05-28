<?php namespace Lovata\Toolbox\Classes\Parser\Create;

/**
 * Class MigrationCreateFile
 * @package Lovata\Toolbox\Classes\Parser\Create
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class MigrationCreateFile extends CommonCreateFile
{
    /** @var string */
    protected $sFile = 'create_table_{{lower_controller}}.php';
    /** @var string */
    protected $sFolderPath = '/{{lower_author}}/{{lower_plugin}}/updates/';
    /** @var string */
    protected $sTemplatePath = '/lovata/toolbox/classes/parser/templates/migration.stub';
}
