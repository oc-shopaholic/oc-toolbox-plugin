<?php namespace Lovata\Toolbox\Classes\Console;

use Lang;
use Lovata\Toolbox\Classes\Parser\CollectionFile;

/**
 * Class CreateCollection
 * @package Lovata\Toolbox\Classes\Console
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateCollection extends CommonCreateFile
{
    /** @var string The console command name. */
    protected $name = 'toolbox:create.collection';
    /** @var string The console command description. */
    protected $description = 'Create a new collection.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        $this->setModel();
        $this->setFieldList(null, [self::CODE_ACTIVE, self::CODE_DEFAULT]);
        $this->setSorting();
        $this->createFile(CollectionFile::class);
    }
}
