<?php namespace Lovata\Toolbox\Classes\Api\Type\Enum;

/**
 * Class ResizeImageModeEnumType
 * @package Lovata\Toolbox\Classes\Api\Type\Enum
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class ResizeImageModeEnumType extends AbstractEnumType
{
    const TYPE_ALIAS = 'ResizeImageMode';

    const MODE_AUTO = 'auto';
    const MODE_EXACT = 'exact';
    const MODE_PORTRAIT = 'portrait';
    const MODE_LANDSCAPE = 'landscape';
    const MODE_CROP = 'crop';
    const MODE_FIT = 'fit';

    /** @var ResizeImageModeEnumType */
    protected static $instance;

    /**
     * @inheritDoc
     */
    protected function getValueList(): array
    {
        $arValueList = [
            'AUTO' => [
                'value' => self::MODE_AUTO,
                'description' => 'Automatically choose between `portrait` and `landscape` based on the image\'s orientation',
            ],
            'EXACT' => [
                'value' => self::MODE_EXACT,
                'description' => 'Resize to the exact dimensions given, without preserving aspect ratio',
            ],
            'PORTRAIT' => [
                'value' => self::MODE_PORTRAIT,
                'description' => 'Resize to the given height and adapt the width to preserve aspect ratio',
            ],
            'LANDSCAPE' => [
                'value' => self::MODE_LANDSCAPE,
                'description' => 'Resize to the given width and adapt the height to preserve aspect ratio',
            ],
            'CROP' => [
                'value' => self::MODE_CROP,
                'description' => 'Crop to the given dimensions after fitting as much of the image as possible inside those',
            ],
            'FIT' => [
                'value' => self::MODE_FIT,
                'description' => 'Fit the image inside the given maximal dimensions, keeping the aspect ratio',
            ],
        ];

        return $arValueList;
    }

    /**
     * @inheritDoc
     */
    protected function getDescription(): string
    {
        return 'The mode option allows you to specify how the image should be resized. The available modes are as follows:';
    }
}
