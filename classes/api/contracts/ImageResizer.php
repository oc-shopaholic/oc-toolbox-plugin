<?php namespace Lovata\Toolbox\Classes\Api\Contracts;

use System\Models\File;

interface ImageResizer
{
    public function setFile(File $obFile): self;
    public function getFile(): File;
    public function getImageUrl(array $arSizeData): string;
    public function getImageUrlList(array $arSizesData): array;
    public function clean(): void;
}
