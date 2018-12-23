<?php namespace Lovata\Toolbox\Classes\Console;

use Lovata\Toolbox\Classes\Parser\ListStoreFile;
use Lovata\Toolbox\Classes\Parser\ActiveListStoreFile;
use Lovata\Toolbox\Classes\Parser\SortingListStoreFile;
use Lovata\Toolbox\Classes\Parser\TopLevelListStoreFile;

/**
 * Class CreateStore
 * @package Lovata\Toolbox\Classes\Console
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateStore extends CommonCreateFile
{
    /** @var string The console command name. */
    protected $name = 'toolbox:create.store';
    /** @var string The console command description. */
    protected $description = 'Create a new store.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        $this->setAuthor(true);
        $this->setPlugin(true);
        $this->setModel();
        $this->setFieldList(null, [self::CODE_ACTIVE, self::CODE_VIEW_COUNT, self::CODE_DEFAULT]);
        $this->setSorting();
        $this->createFile(ListStoreFile::class);

        if ($this->checkEnableList(self::CODE_ACTIVE)) {
            $this->createFile(ActiveListStoreFile::class);
        }

        if ($this->checkEnableList(self::CODE_SORTABLE) || $this->checkEnableList(self::CODE_DEFAULT_SORTING)) {
            $this->createFile(SortingListStoreFile::class);
        }

        if ($this->checkEnableList(self::CODE_NESTED_TREE)) {
            $this->createFile(TopLevelListStoreFile::class);
        }
    }
}
