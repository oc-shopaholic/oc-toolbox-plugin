<?php namespace Lovata\Toolbox\Classes\Api\Type\Input;

use GraphQL\Type\Definition\Type;
use Lovata\Toolbox\Classes\Api\Type\Enum\ResizeImageModeEnumType;

/**
 * Class ResizeImageInputType
 * @package Lovata\Toolbox\Classes\Api\Type\Input
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class ResizeImageInputType extends AbstractInputType
{
    const TYPE_ALIAS = 'ResizeImageInput';

    /** @var ResizeImageInputType */
    protected static $instance;

    /**
     * @inheritDoc
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'width'      => [
                'type'         => Type::int(),
                'description'  => 'Image width',
                'defaultValue' => null,
            ],
            'height'     => [
                'type'         => Type::int(),
                'description'  => 'Image height',
                'defaultValue' => null,
            ],
            'quality'    => [
                'type'         => Type::int(),
                'description'  => 'Image quality, from 0 - 100 (default: 90)',
                'defaultValue' => 90,
            ],
            'mode'       => [
                'type'         => $this->getRelationType(ResizeImageModeEnumType::TYPE_ALIAS),
                'description'  => 'How the image should be fitted to dimensions',
                'defaultValue' => ResizeImageModeEnumType::MODE_AUTO,
            ],
            'extension'  => [
                'type'        => Type::string(),
                'description' => 'Image file extension to convert',
            ],
            'offsetLeft' => [
                'type'         => Type::int(),
                'description'  => 'Left offset the crop of the resized image',
                'defaultValue' => 0,
            ],
            'offsetTop'  => [
                'type'         => Type::int(),
                'description'  => 'Top offset the crop of the resized image',
                'defaultValue' => 0,
            ],
            'sharpen'    => [
                'type'         => Type::int(),
                'description'  => 'Sharpen image, from 0 - 100 (default: 0)',
                'defaultValue' => 0,
            ],
            'interlace'  => [
                'type'         => Type::boolean(),
                'description'  => 'Interlace image,  Boolean: false (disabled: default), true (enabled)',
                'defaultValue' => false,
            ],
        ];

        return $arFieldList;
    }

    protected function getDescription(): string
    {
        return 'Resize image input data';
    }
}
