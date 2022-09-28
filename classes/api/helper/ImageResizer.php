<?php namespace Lovata\Toolbox\Classes\Api\Helper;

use RuntimeException;
use System\Models\File;
use Lovata\Toolbox\Classes\Api\Contracts\ImageResizer as ImageResizerContract;

/**
 * Class ImageResizer
 * @package Lovata\Toolbox\Classes\Api\Helper
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
abstract class ImageResizer implements ImageResizerContract
{
    private ?File $obFile = null;

    /**
     * Set file to resize
     * @param File $obFile
     * @return $this
     */
    public function setFile(File $obFile): self
    {
        $this->obFile = $obFile;

        return $this;
    }

    /**
     * Get file
     * @return File
     */
    public function getFile(): File
    {
        if ($this->obFile === null) {
            throw new RuntimeException("File not setting to resizer");
        }

        return $this->obFile;
    }


    /**
     * Get image URL list
     * @param array $arSizesData
     * @return array
     */
    public function getImageUrlList(array $arSizesData): array
    {
        $arResizeImageUrlList = [];
        foreach ($arSizesData as $arSizeData) {
            $arResizeImageUrlList[] = $this->getImageUrl($arSizeData);
        }

        return $arResizeImageUrlList;
    }

    /**
     * Clean file from memory
     * @return void
     */
    public function clean(): void
    {
        $this->obFile = null;
    }
}
