<?php namespace Lovata\Toolbox\Classes\Api\Type;

/**
 * Class AbstractObjectType
 * @package Lovata\Toolbox\Classes\Api\Type
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
abstract class AbstractObjectType extends AbstractApiType
{
    const TYPE = TypeList::OBJECT_TYPE;

    /** @var array $arInterfaceList */
    protected $arInterfaceList = [];

    /** @var array|null $arFieldList */
    protected $arFieldList = [];

    /** @var array $arArgumentList */
    protected $arArgumentList;

    /**
     * @throws \Exception
     */
    protected function init()
    {
        $this->arInterfaceList = $this->getInterfaceList();
        $this->arFieldList     = $this->getFieldList();
        $this->arArgumentList  = $this->getArguments();
        parent::init();
    }

    /**
     * Get type fields
     * @return array
     */
    abstract protected function getFieldList(): array;

    /**
     * Get config for "args" attribute
     * @return array|null
     */
    protected function getArguments(): ?array
    {
        return null;
    }

    /**
     * Add fields
     * @param array $arFieldList
     * @return void
     */
    public function addFields(array $arFieldList)
    {
        $this->arFieldList = array_merge($this->arFieldList, $arFieldList);
    }

    /**
     * Remove fields
     * @param array $arFieldList
     * @return void
     */
    public function removeFields(array $arFieldList)
    {
        if (empty($arFieldList)) {
            return;
        }

        foreach ($arFieldList as $sKey) {
            unset($this->arFieldList[$sKey]);
        }
    }

    /**
     * Add arguments
     * @param array $arArgumentList
     * @return void
     */
    public function addArguments(array $arArgumentList)
    {
        if (is_null($this->arArgumentList)) {
            $this->arArgumentList = $arArgumentList;

            return;
        }

        $this->arArgumentList = array_merge($this->arArgumentList, $arArgumentList);
    }

    /**
     * Remove arguments
     * @param array $arArgumentList
     * @return void
     */
    public function removeArguments(array $arArgumentList)
    {
        if (empty($this->arArgumentList) || empty($arArgumentList)) {
            return;
        }

        foreach ($arArgumentList as $sKey) {
            unset($this->arArgumentList[$sKey]);
        }
    }

    /**
     * Get interface fields
     * @return array
     */
    protected function getInterfaceList(): array
    {
        return [];
    }

    /**
     * Get type config
     * @return array
     */
    protected function getTypeConfig(): array
    {
        $arTypeConfig = [
            'name'        => static::TYPE_ALIAS,
            'fields'      => $this->arFieldList,
            'interfaces'  => $this->arInterfaceList,
            'description' => $this->sDescription,
        ];

        return $arTypeConfig;
    }

    /**
     * Get resolve method for type
     * @return callable|null
     */
    protected function getResolveMethod(): ?callable
    {
        return null;
    }
}
