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

class MediaCollectionToArrayTransformer implements DataTransformerInterface
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
     * @param string        $mediaClass
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
    public function transform($medias)
    {
        if (null === $medias) {
            return array();
        }

        $ids = array();
        foreach ($medias as $media) {
            $ids[] = $media->getId();
        }

        return implode(';', $ids);
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
    public function reverseTransform($array)
    {
        $medias = new \Doctrine\Common\Collections\ArrayCollection();

        if (!$array || !preg_match('#^[0-9]+(;[0-9])*$#', $array)) {
            return $medias;
        }

        $ids = explode(';', $array);

        foreach ($ids as $id) {
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

            $medias->add($media);
        }

        return $medias;
    }
}
