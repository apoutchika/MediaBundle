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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Apoutchika\MediaBundle\Form\DataTransformer\MediaToIdTransformer;
use Apoutchika\MediaBundle\Services\ContextsManipulator;
use Apoutchika\MediaBundle\Services\Filter;

/**
 * class ApoutchikaMediaOneType.
 *
 * Create field for media
 */
class ApoutchikaMediaOneType extends AbstractType
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
     * @var ContextManipulator
     */
    private $contextsManipulator;

    /**
     * @var Filter
     */
    private $filter;

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
     * Set Filter.
     *
     * @param Filter $filter
     */
    public function setFilter(Filter $filter)
    {
        $this->filter = $filter;
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
     * Set contexts.
     *
     * @param array $contexts List of contexts
     */
    public function setContexts(array $contexts)
    {
        $this->contextsManipulator = new ContextsManipulator($contexts);
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
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['filter'] = $this->filter->set($options['filter']);

        $this->contextsManipulator
            ->addContexts($options['contexts'])
            ->addAllowedExtensions($options['allowed_extensions']);

        $view->vars['allowed_extensions'] = $this->contextsManipulator->getAllowedExtensions();
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'contexts' => false,
            'allowed_extensions' => array(),
            'filter' => null,
            'error_bubbling' => false,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'hidden';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'apoutchika_media_one';
    }
}
