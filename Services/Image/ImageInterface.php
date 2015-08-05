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
use Apoutchika\MediaBundle\Manager\MediaManager;
use Apoutchika\MediaBundle\Model\MediaInterface;

interface ImageInterface
{
    /**
     * @param MediaManager   $mediaManager
     * @param MediaInterface $media
     */
    public function __construct(MediaManager $mediaManager, MediaInterface $media);

    /**
     * Apply the filter.
     *
     * @param string $aliasName
     */
    public function apply($aliasName);

    /**
     * @param ImageInterface $image
     *
     * @return ImageInterface
     */
    public function updateFile(ImagineImageInterface $image);

    /**
     * Update the focus after update.
     *
     * @param MediaInterface $media
     */
    public function updateFocus(MediaInterface $media);
}
