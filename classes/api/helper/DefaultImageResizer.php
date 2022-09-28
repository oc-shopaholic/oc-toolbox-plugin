<?php namespace Lovata\Toolbox\Classes\Api\Helper;

use Illuminate\Support\Arr;

/**
 * Class DefaultImageResizer
 * @package Lovata\Toolbox\Classes\Api\Helper
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class DefaultImageResizer extends ImageResizer
{
    /**
     * Get image URL
     * @param array $arSizeData
     * @return string
     */
    public function getImageUrl(array $arSizeData): string
    {
        $arOptions = Arr::only($arSizeData, ['mode', 'sharpen', 'interlace', 'quality']);
        $arOptions['extension'] = Arr::get($arSizeData, 'extension', $this->getFile()->getExtension());
        $arOptions['offset'] = [(int) $arSizeData['offsetLeft'], (int) $arSizeData['offsetTop']];

        return $this->getFile()->getThumb((int) $arSizeData['width'], (int) $arSizeData['height'], $arOptions);
    }
}
