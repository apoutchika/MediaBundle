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

use Apoutchika\MediaBundle\Manager\MediaManager;
use Apoutchika\MediaBundle\Model\MediaInterface;

abstract class BaseImage implements ImageInterface
{
    /**
     * @var MediaManager
     */
    protected $mediaManager;

    /**
     * @var MediaInterface
     */
    protected $media;

    /**
     * @var ImagineInterface
     */
    protected $imagine;

    /**
     * @var image instance
     */
    protected $image;

    /**
     * @param MediaManager   $mediaManager
     * @param MediaInterface $media
     */
    public function __construct(MediaManager $mediaManager, MediaInterface $media)
    {
        $this->mediaManager = $mediaManager;
        $this->media = $media;
        $this->imagine = $mediaManager->getImagine();
        $this->image = $this->imagine->load($mediaManager->getContent($media));
    }

    /**
     * Apply the filter.
     *
     * @param string $aliasName
     */
    public function apply($aliasName = null)
    {
        $reset = false;
        if ($aliasName === null) {
            $aliasName = $this->mediaManager->getOriginalDir();
            $reset = true;
        }

        $this->updateFile($this->image);
        $this->updateFocus($this->media);

        $fs = $this
            ->mediaManager
            ->getFilesystemManipulator();

        $fs->saveContent(
            $aliasName.'/'.$this->media->getReference(),
            $this->image->get($this->media->getExtension())
        );

        $this->mediaManager
            ->setSizeWidthAndHeight($this->media)
            ->save($this->media);

        if ($reset === true) {
            $this->mediaManager->reset($this->media);
        }
    }
}
