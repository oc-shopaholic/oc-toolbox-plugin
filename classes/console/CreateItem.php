<?php namespace Lovata\Toolbox\Classes\Console;

use Lovata\Toolbox\Classes\Parser\Create\ItemCreateFile;

/**
 * Class CreateItem
 * @package Lovata\Toolbox\Classes\Console
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateItem extends CommonCreateFile
{
    /** @var string The console command name. */
    protected $name = 'toolbox:create.item';
    /** @var string The console command description. */
    protected $description = 'Create a new item.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        $this->setModel();
        $this->setFieldList();
        $this->setSorting([self::CODE_DEFAULT_SORTING]);
        $this->createFile(ItemCreateFile::class);
    }
}
