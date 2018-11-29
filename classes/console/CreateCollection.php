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

        if (empty($this->arInoutData)) {
            $this->logoToolBox();
            $this->setAuthor();
            $this->setPlugin();
        }

        if (!$this->checkAddition(self::CODE_MODEL)) {
            $this->setModel();
        }

        $this->additionalProperty(
            self::CODE_ACTIVE,
            'lovata.toolbox::lang.message.active_list'
        );
        $this->additionalProperty(
            self::CODE_SORT,
            'lovata.toolbox::lang.message.sort_list'
        );

        $this->createFile(CollectionFile::class);
    }

    /**
     * Add additional property list
     * @param string $sCode
     * @param string $sCodeMessage
     */
    protected function additionalProperty($sCode, $sCodeMessage)
    {
        $this->arData['enable'][] = $sCode;
        if (!$this->checkAddition($sCode)) {
            $sMessage = Lang::get($sCodeMessage, ['class' => self::CODE_COLLECTION]);
            if (!$this->confirm($sMessage, true)) {
                $this->arData['disable'][] = $sCode;
            }
        }
    }
}