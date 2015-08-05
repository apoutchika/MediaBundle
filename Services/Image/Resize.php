<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\Services\Image;

use Imagine\Image\ImageInterface as ImagineImageInterface;
use Apoutchika\MediaBundle\Model\MediaInterface;
use Imagine\Filter\Basic\Resize as ImagineResize;
use Imagine\Image\Box;

class Resize extends BaseImage
{
    /**
     * @var string
     */
    private $targetPath;

    /**
     * @param int
     */
    private $targetWidth,
            $targetHeight;

    /**
     * @param string $targetPath
     *
     * @return Resize
     */
    public function setTargetPath($targetPath)
    {
        $this->targetPath = $targetPath;

        return $this;
    }

    /**
     * @param int $targetWidth
     * @param int $targetHeight
     *
     * @return Resize
     */
    public function setTargetSize($targetWidth, $targetHeight)
    {
        $this->targetWidth = $targetWidth;
        $this->targetHeight = $targetHeight;

        return $this;
    }

    /**
     * @param ImagineImageInterface $image
     *
     * @return ImagineImagineInterface
     */
    public function updateFile(ImagineImageInterface $image = null)
    {
        if ($image === null) {
            $image = $this->image;
        }

        $target = $this->getTargetSize(
            $image->getSize()->getWidth(),
            $image->getSize()->getHeight(),
            $this->targetWidth,
            $this->targetHeight
        );

        $resize = new ImagineResize(
            new Box($target['width'], $target['height'])
        );

        $image = $resize->apply($image);

        return $image;
    }

    /**
     * Update the focus after update.
     *
     * @param MediaInterface $media
     */
    public function updateFocus(MediaInterface $media)
    {
        // no edit
    }

    /**
     * @param int $originalWidth
     * @param int $originalHeight
     * @param int $targetWidth
     * @param int $targetHeight
     *
     * @return array Width and Height
     */
    private function getTargetSize($originalWidth, $originalHeight, $targetWidth, $targetHeight)
    {
        if ($targetWidth === null) {
            return $this->resizeByHeight($originalWidth, $originalHeight, $targetHeight);
        }

        if ($targetHeight === null) {
            return $this->resizeByWidth($originalWidth, $originalHeight, $targetWidth);
        }

        $diffWidth = $originalWidth / $targetWidth;
        $diffHeight = $originalHeight / $targetHeight;

        if ($diffWidth <= $diffHeight) {
            return $this->resizeByHeight($originalWidth, $originalHeight, $targetHeight);
        }

        if ($diffWidth > $diffHeight) {
            return $this->resizeByWidth($originalWidth, $originalHeight, $targetWidth);
        }
    }

    /**
     * Get Resize size when width > height.
     *
     * @param int $originalWidth
     * @param int $originalHeight
     * @param int $targetWidth
     *
     * @return array Width and Height
     */
    private function resizeByWidth($originalWidth, $originalHeight, $targetWidth)
    {
        $targetHeight = ($targetWidth * $originalHeight) / $originalWidth;

        return $this->verifySize($originalWidth, $originalHeight, $targetWidth, $targetHeight);
    }

    /**
     * get resize size when height > width.
     *
     * @param int $originalWidth
     * @param int $originalHeight
     * @param int $targetHeight
     *
     * @return array Width and Height
     */
    private function resizeByHeight($originalWidth, $originalHeight, $targetHeight)
    {
        $targetWidth = ($targetHeight * $originalWidth) / $originalHeight;

        return $this->verifySize($originalWidth, $originalHeight, $targetWidth, $targetHeight);
    }

    /**
     * Verify if the target size > originial size, if true return original size.
     *
     * @param int $originalWidth
     * @param int $originalHeight
     * @param int $targetWidth
     * @param int $targetHeight
     *
     * @return array Width and Height
     */
    private function verifySize($originalWidth, $originalHeight, $targetWidth, $targetHeight)
    {
        if ($originalWidth < $targetWidth && $originalHeight < $targetHeight) {
            return array(
                'width' => $originalWidth,
                'height' => $originalHeight,
            );
        }

        return array(
            'width' => $targetWidth,
            'height' => $targetHeight,
        );
    }
}
