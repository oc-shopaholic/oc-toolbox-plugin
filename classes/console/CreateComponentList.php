<?php namespace Lovata\Toolbox\Classes\Console;

use Lovata\Toolbox\Traits\Console\UpdateLangFile;
use Lovata\Toolbox\Classes\Parser\Create\ComponentListCreateFile;

/**
 * Class CreateComponentList
 * @package Lovata\Toolbox\Classes\Console
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateComponentList extends CommonCreateFile
{
    use UpdateLangFile;

    /** @var string The console command name. */
    protected $name = 'toolbox:create.component.list';
    /** @var string The console command description. */
    protected $description = 'Create a new component list.';
    /** @var array */
    protected $arLangData = [
        'component' => [
            '{{lower_model}}_list_name'        => '{{studly_model}} List',
            '{{lower_model}}_list_description' => 'Get {{lower_model}} list',
        ],
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        $this->setModel();
        $this->setSorting([self::CODE_NESTED_TREE, self::CODE_SORTABLE]);
        $this->createFile(ComponentListCreateFile::class);
        $this->updatePluginLang($this->arLangData);
    }
}
