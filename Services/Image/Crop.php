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

class Crop extends BaseImage
{
    /**
     * @var int
     */
    private $x,
        $y,
        $w,
        $h;

    /**
     * @param int $x
     * @param int $y
     * @param int $w
     * @param int $h
     *
     * @return Crop
     */
    public function setSize($x, $y, $w, $h)
    {
        $this->x = $x;
        $this->y = $y;
        $this->w = $w;
        $this->h = $h;

        return $this;
    }

    /**
     * @param ImagineImageInterface $image
     *
     * @return ImagineImageInterface
     */
    public function updateFile(ImagineImageInterface $image)
    {
        $image
            ->crop(
                new Point($this->x, $this->y),
                new Box($this->w, $this->h)
            );

        return $image;
    }

    /**
     * Update the focus after update.
     *
     * @param MediaInterface $media
     */
    public function updateFocus(MediaInterface $media)
    {
        $originalFocusLeftPx = ($media->getFocusLeft() * $media->getWidth()) / 100;
        $newFocusLeftPx = $originalFocusLeftPx - $this->x;
        $newFocusLeft = ($newFocusLeftPx * 100) / $this->w;

        if ($newFocusLeft < 0) {
            $newFocusLeft = 0;
        }
        if ($newFocusLeft > 100) {
            $newFocusLeft = 100;
        }

        $media->setFocusLeft($newFocusLeft);

        $originalFocusTopPx = ($media->getFocusTop() * $media->getHeight()) / 100;
        $newFocusTopPx = $originalFocusTopPx - $this->y;
        $newFocusTop = ($newFocusTopPx * 100) / $this->h;

        if ($newFocusTop < 0) {
            $newFocusTop = 0;
        }
        if ($newFocusTop > 100) {
            $newFocusTop = 100;
        }

        $media->setFocusTop($newFocusTop);
    }
}
