<?php namespace Lovata\Toolbox\Classes\Console;

use Lovata\Toolbox\Classes\Parser\Create\ModelColumnCreateFile;

/**
 * Class CreateModelColumn
 * @package Lovata\Toolbox\Classes\Console
 * @author Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateModelColumn extends CommonCreateFile
{
    /** @var string The console command name. */
    protected $name = 'toolbox:create.model.columns';
    /** @var string The console command description. */
    protected $description = 'Create a new columns model.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        $this->setModel();
        $this->setFieldList([self::CODE_PREVIEW_IMAGE, self::CODE_IMAGES, self::CODE_FILE]);
        $this->setSorting([self::CODE_DEFAULT_SORTING, self::CODE_NESTED_TREE]);
        $this->createFile(ModelColumnCreateFile::class);
    }
}
