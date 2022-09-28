<?php namespace Lovata\Toolbox\Classes\Api\Type\Interfaces;

use Exception;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Arr;
use Lovata\Toolbox\Classes\Api\Type\Custom\FileType;
use Lovata\Toolbox\Classes\Api\Type\Custom\ImageFileType;

/**
 * Class FileInterfaceType
 * @package Lovata\Toolbox\Classes\Api\Type\Interfaces
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class FileInterfaceType extends AbstractInterfaceType
{
    const TYPE_ALIAS = 'FileInterface';

    /** @var FileInterfaceType */
    protected static $instance;

    /**
     * @inheritDoc
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'url'         => [
                'type' => Type::string(),
                'description' => 'File URL',
                'resolve' => function ($obFile) {
                    return $obFile->getPath();
                }
            ],
            'title'       => [
                'type' => Type::string(),
                'description' => 'File title',
                'resolve' => function ($obFile) {
                    return Arr::get($obFile->attributes, 'title');
                }
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'File description',
                'resolve' => function ($obFile) {
                    return Arr::get($obFile->attributes, 'description');
                },

            ],
            'file_name'   => [
                'type' => Type::string(),
                'description' => 'File name',
                'resolve' => function ($obFile) {
                    return Arr::get($obFile->attributes, 'file_name');
                }
            ],
        ];

        return $arFieldList;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function getResolveType($obObjectType): ObjectType
    {
        $sTypeName = $obObjectType->type ?? null;

        switch ($sTypeName) {
            case FileType::TYPE_ALIAS:
                return $this->getRelationType(FileType::TYPE_ALIAS);
            case ImageFileType::TYPE_ALIAS:
                return $this->getRelationType(ImageFileType::TYPE_ALIAS);
            default:
                throw new Exception("Unknown object type: {$sTypeName}");
        }
    }

    /**
     * @inheritDoc
     */
    protected function getDescription(): string
    {
        return 'File type interface'; //TODO: add translations
    }
}
