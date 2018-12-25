<?php namespace Lovata\Toolbox\Classes\Console;

use Lovata\Toolbox\Classes\Parser\Create\ListStoreCreateFile;
use Lovata\Toolbox\Classes\Parser\Create\ActiveListStoreCreateFile;
use Lovata\Toolbox\Classes\Parser\Create\SortingListStoreCreateFile;
use Lovata\Toolbox\Classes\Parser\Create\TopLevelListStoreCreateFile;

/**
 * Class CreateStore
 * @package Lovata\Toolbox\Classes\Console
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
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
        $this->createFile(ListStoreCreateFile::class);

        if ($this->checkEnableList(self::CODE_ACTIVE)) {
            $this->createFile(ActiveListStoreCreateFile::class);
        }

        if ($this->checkEnableList(self::CODE_SORTABLE) || $this->checkEnableList(self::CODE_DEFAULT_SORTING)) {
            $this->createFile(SortingListStoreCreateFile::class);
        }

        if ($this->checkEnableList(self::CODE_NESTED_TREE)) {
            $this->createFile(TopLevelListStoreCreateFile::class);
        }
    }
}
