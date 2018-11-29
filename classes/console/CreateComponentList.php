<?php namespace Lovata\Toolbox\Classes\Console;

use Lovata\Toolbox\Classes\Parser\ComponentList;

/**
 * Class CreateComponentList
 * @package Lovata\Toolbox\Classes\Console
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateComponentList extends CommonCreateFile
{
    /** @var string The console command name. */
    protected $name = 'toolbox.create.component.list';
    /** @var string The console command description. */
    protected $description = 'Create a new component list.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        if (empty($this->arInoutData)) {
            $this->logoToolBox();
            $this->setAuthor();
            $this->setPlugin();
        }

        if (!$this->checkAddition(self::CODE_MODEL)) {
            $this->setModel();
        }

        $this->createFile(ComponentList::class);
    }
}
