<?php namespace Lovata\Toolbox\Classes\Api\Type\Custom;

use Validator;
use System\Models\File;
use Illuminate\Support\Arr;

use GraphQL\Error\UserError;
use GraphQL\Type\Definition\Type;

use Lovata\Toolbox\Classes\Api\Contracts\ImageResizer;
use Lovata\Toolbox\Classes\Api\Type\AbstractObjectType;
use Lovata\Toolbox\Classes\Api\Type\Input\ResizeImageInputType;
use Lovata\Toolbox\Classes\Api\Type\Interfaces\FileInterfaceType;

/**
 * Class ImageFileType
 * @package Lovata\Toolbox\Classes\Api\Type\Object
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class ImageFileType extends AbstractObjectType
{
    const TYPE_ALIAS = 'ImageFile';

    /** @var ImageFileType */
    protected static $instance;

    /**
     * @inheritDoc
     * @throws \GraphQL\Error\Error
     */
    protected function getInterfaceList(): array
    {
        return [
            $this->getRelationType(FileInterfaceType::TYPE_ALIAS),
        ];
    }

    /**
     * @inheritDoc
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $obFileInterfaceType = $this->getRelationType(FileInterfaceType::TYPE_ALIAS);
        $arFileInterfaceFieldList = $obFileInterfaceType->getFields();

        $arImageFileFieldList = [
            'resizer' => [
                'type'        => Type::listOf(Type::string()),
                'args'        => [
                    'input' => [
                        'type'        => Type::nonNull(
                            Type::listOf($this->getRelationType(ResizeImageInputType::TYPE_ALIAS))
                        ),
                        'description' => 'Input parameters for resize image',
                    ],
                ],
                'description' => 'Resize image',
                'resolve'     => function (File $obImageFile, $arArgumentList) {
                    $arResizeInput = (array) Arr::get($arArgumentList, 'input');
                    $this->validateArguments($arResizeInput);
                    $arResult = app(ImageResizer::class)->setFile($obImageFile)->getImageUrlList($arResizeInput);
                    app(ImageResizer::class)->clean();

                    return $arResult;
                }
            ],
        ];

        return array_merge($arFileInterfaceFieldList, $arImageFileFieldList);
    }

    /**
     * Validate resize arguments
     * @param $arResizeInput
     * @return void
     */
    protected function validateArguments($arResizeInput)
    {
        $arErrorMessageList = [];
        foreach ($arResizeInput as $arSizeData) {
            $arValidationRules = [
                'quality' => 'integer|between:0,100',
                'sharpen' => 'integer|between:0,100',
            ];

            $iWidth = (int) $arSizeData['width'];
            $iHeight = (int) $arSizeData['height'];
            $arValidationRules['offsetTop'] = $iHeight > 0 ? 'integer|between:0,' . $iHeight : '';
            $arValidationRules['offsetLeft'] = $iWidth > 0 ? 'integer|between:0,' . $iWidth : '';

            $obValidator = Validator::make($arSizeData, $arValidationRules);

            if ($obValidator->fails()) {
                $arErrorMessageList[] = $obValidator->errors()->jsonSerialize();
            }
        }

        if (!empty($arErrorMessageList)) {
            throw new UserError(json_encode($arErrorMessageList, JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * @inheritDoc
     */
    protected function getDescription(): string
    {
        return 'Image file';
    }
}
