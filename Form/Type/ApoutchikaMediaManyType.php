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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Apoutchika\MediaBundle\Services\ContextsManipulator;
use Apoutchika\MediaBundle\Services\Filter;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\AbstractTypeExtension;

/**
 * class ApoutchikaMediaManyType.
 *
 * Create field collection for media
 */
class ApoutchikaMediaManyType extends AbstractType
{
    /**
     * @var ContextManipulator
     */
    private $contextsManipulator;

    /**
     * @var Filter
     */
    private $filter;

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
     * Set Filter.
     *
     * @param Filter $filter
     */
    public function setFilter(Filter $filter)
    {
        $this->filter = $filter;
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'type' => 'apoutchika_media_one_for_collection',
            'allow_add' => true,
            'allow_delete' => true,
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
        return CollectionType::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return CollectionType::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'apoutchika_media_many';
    }
}
