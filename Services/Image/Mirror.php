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

class Mirror extends BaseImage
{
    /**
     * @var string|null 'x'|'y'|null
     */
    private $direction = null;

    /**
     * Set direction.
     *
     * @param string $direction 'x'|'y'
     */
    public function setDirection($direction = null)
    {
        if (in_array($direction, array('x', 'y'))) {
            $this->direction = $direction;
        }

        return $this;
    }

    /**
     * @param ImagineImageInterface $image
     *
     * @return ImagineImageInterface
     */
    public function updateFile(ImagineImageInterface $image)
    {
        if ($this->direction !== null) {
            $transformation = new Transformation();

            if ($this->direction == 'x') {
                $transformation->flipHorizontally();
            } elseif ($this->direction == 'y') {
                $transformation->flipVertically();
            }

            $transformation->apply($image);
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
        if ($this->direction == 'x') {
            $media->setFocusLeft(100 - $media->getFocusLeft());
        } elseif ($this->direction == 'y') {
            $media->setFocusTop(100 - $media->getFocusTop());
        }
    }
}
