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
use Imagine\Filter\Transformation;

class Rotate extends BaseImage
{
    /**
     * @var int
     */
    private $angle = null;

    /**
     * @param int $angle
     *
     * @return Rotate
     */
    public function setAngle($angle)
    {
        $this->angle = $angle;

        return $this;
    }

    /**
     * @param ImagineImageInterface $image
     *
     * @return ImagineImagineInterface
     */
    public function updateFile(ImagineImageInterface $image)
    {
        if ($this->angle !== null && is_int($this->angle)) {
            $transformation = new Transformation();
            $transformation->rotate($this->angle, null);
            $transformation
                ->apply($image)
                ;
        }

        return $image;
    }

    /**
     * Update the focus after update.
     *
     * @param MediaInterface $media
     */
    public function updateFocus(MediaInterface $media)
    {
        if ($this->angle == 90) {
            $focusLeft = 100 - $media->getFocusTop();
            $focusTop = $media->getFocusLeft();
        } elseif ($this->angle == -90) {
            $focusLeft = $media->getFocusTop();
            $focusTop = 100 - $media->getFocusLeft();
        }

        $media->setFocusLeft($focusLeft);
        $media->setFocusTop($focusTop);
    }
}
