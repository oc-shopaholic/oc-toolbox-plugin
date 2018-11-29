<?php namespace Lovata\Toolbox\Classes\Console;

use Lang;
use Lovata\Toolbox\Classes\Parser\EventModelFile;

/**
 * Class CreateEventModel
 * @package Lovata\Toolbox\Classes\Console
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CreateEventModel extends CommonCreateFile
{
    /** @var string The console command name. */
    protected $name = 'toolbox:create.event.model';
    /** @var string The console command description. */
    protected $description = 'Create a new event model.';

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

        $this->createFile(EventModelFile::class);
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

        $arDisableList = array_get($this->arData, 'disable');

        $sActiveSort = 'active_sort';
        $this->arData['enable'][] = $sActiveSort;

        if (!empty($arDisableList) && in_array(self::CODE_ACTIVE, $arDisableList) && in_array(self::CODE_SORT, $arDisableList)) {
            $this->arData['disable'][] = $sActiveSort;
        }
    }
}