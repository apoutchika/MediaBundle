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
use Imagine\Image\Point;
use Imagine\Image\Box;
use Imagine\Filter\Basic\Resize as ImagineResize;
use Imagine\Filter\Basic\Crop as ImagineCrop;

class Focus extends BaseImage
{
    /**
     * @param int
     */
    private $resizeWidth,
        $resizeHeight,

        $originWidth,
        $originHeight,

        $targetWidth,
        $targetHeight,

        $focusLeft,
        $focusTop,

        $targetLeft = 0,
        $targetTop = 0;

    /**
     * @var string
     */
    private $targetPath;

    /**
     * @param string $targetPath
     *
     * @return Focus
     */
    public function setTargetPath($targetPath)
    {
        $this->targetPath = $targetPath;

        return $this;
    }

    /**
     * @param int $originWidth
     * @param int $originHeight
     *
     * @return Focus
     */
    public function setOriginSize($originWidth, $originHeight)
    {
        $this->originWidth = $originWidth;
        $this->originHeight = $originHeight;

        return $this;
    }

    /**
     * @param int $targetWidth
     * @param int $targetHeight
     *
     * @return Focus
     */
    public function setTargetSize($targetWidth, $targetHeight)
    {
        $this->targetWidth = $targetWidth;
        $this->targetHeight = $targetHeight;

        return $this;
    }

    /**
     * @param int $focusLeft
     * @param int $focusTop
     *
     * @return Focus
     */
    public function setFocus($focusLeft, $focusTop)
    {
        $this->focusLeft = $focusLeft;
        $this->focusTop = $focusTop;

        return $this;
    }

    /**
     * @param ImagineImageInterface $image
     *
     * @return ImagineImageInterface
     */
    public function updateFile(ImagineImageInterface $image)
    {
        $this->originWidth = $image->getSize()->getWidth();
        $this->originHeight = $image->getSize()->getHeight();

        // Resize 
        $this->resize();

        $resize = new ImagineResize(
            new Box($this->resizeWidth, $this->resizeHeight)
        );
        $image = $resize->apply($image);

        // Crop
        $this->crop();
        $crop = new ImagineCrop(
            new Point($this->targetLeft, $this->targetTop),
            new Box($this->targetWidth, $this->targetHeight)
        );

        $image = $crop->apply($image);

        return $image;
    }

    /**
     * Resize image.
     *
     * @return Focus
     */
    public function resize()
    {
        $diffW = $this->originWidth / $this->targetWidth;
        $diffH = $this->originHeight / $this->targetHeight;

        if ($diffW <= 1 || $diffH <= 1) {
            $this->resizeWidth = $this->originWidth;
            $this->resizeHeight = $this->originHeight;
        } elseif ($diffW > $diffH) {
            $this->resizeHeight = $this->targetHeight;
            $this->resizeWidth = round(($this->targetHeight * $this->originWidth) / $this->originHeight);
        } else {
            $this->resizeWidth = $this->targetWidth;
            $this->resizeHeight = round(($this->targetWidth * $this->originHeight) / $this->originWidth);
        }

        return $this;
    }

    /**
     * Crop image.
     *
     * @return Focus
     */
    public function crop()
    {
        if ($this->resizeWidth > $this->targetWidth) {
            $this->targetLeft = $this->getStartCrop($this->resizeWidth, $this->targetWidth, $this->focusLeft);
        }

        if ($this->resizeHeight > $this->targetHeight) {
            $this->targetTop = $this->getStartCrop($this->resizeHeight, $this->targetHeight, $this->focusTop);
        }

        return $this;
    }

    /**
     * Get start to crop by focus.
     *
     * @param int $origin
     * @param int $target
     * @param int $focus
     *
     * @return int
     */
    public function getStartCrop($origin, $target, $focus)
    {
        $focusPx = round(($origin * $focus) / 100);
        $start = $focusPx - ($target / 2);

        if ($start <= 0) {
            return 0;
        }

        if (($start + $target) >= $origin) {
            return $origin - $target;
        }

        return $start;
    }

    /**
     * @return int
     */
    public function getResizeWidth()
    {
        return $this->resizeWidth;
    }

    /**
     * @return int
     */
    public function getResizeHeight()
    {
        return $this->resizeHeight;
    }

    /**
     * @return int
     */
    public function getTargetLeft()
    {
        return $this->targetLeft;
    }

    /**
     * @return int
     */
    public function getTargetTop()
    {
        return $this->targetTop;
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
}
