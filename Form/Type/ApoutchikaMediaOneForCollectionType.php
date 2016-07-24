<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Apoutchika\MediaBundle\Form\DataTransformer\MediaToIdTransformer;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\AbstractTypeExtension;

/**
 * class ApoutchikaMediaOneForCollectionType.
 *
 * Create specific field for insert in media collection
 */
class ApoutchikaMediaOneForCollectionType extends AbstractType
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var string Media class
     */
    private $mediaClass;

    /**
     * Set EntityManager for modelTransformer.
     *
     * @param EntityManager $entityManager
     */
    public function setEntityManager(\Doctrine\ORM\EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Set media class for modelTransformer.
     *
     * @param string $mediaClass
     */
    public function setMediaClass($mediaClass)
    {
        $this->mediaClass = $mediaClass;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new MediaToIdTransformer($this->entityManager, $this->mediaClass);
        $builder->addModelTransformer($transformer);
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'contexts' => false,
            'allowed_extensions' => array(),
            'filter' => null,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return HiddenType::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return HiddenType::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'apoutchika_media_one_for_collection';
    }
}
