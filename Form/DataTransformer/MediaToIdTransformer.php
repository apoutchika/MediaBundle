<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class MediaToIdTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var string Media class
     */
    private $mediaClass;

    /**
     * @param ObjectManager $objectManager
     * @param String        $mediaClass
     */
    public function __construct(ObjectManager $objectManager, $mediaClass)
    {
        $this->objectManager = $objectManager;
        $this->mediaClass = $mediaClass;
    }

    /**
     * Transforms an object (media) to a string (id).
     *
     * @param Media|null $media
     *
     * @return string
     */
    public function transform($media)
    {
        if (null === $media || !$media instanceof \Apoutchika\MediaBundle\Model\MediaInterface) {
            return '';
        }

        return $media->getId();
    }

    /**
     * Transforms a string (id) to an object (media).
     *
     * @param string $id
     *
     * @return Media|null
     *
     * @throws TransformationFailedException if object (media) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return;
        }

        $media = $this->objectManager
            ->getRepository($this->mediaClass)
            ->find($id)
            ;

        if (null === $media) {
            throw new TransformationFailedException(sprintf(
                'Media "%s" is not found !',
                $id
            ));
        }

        return $media;
    }
}
