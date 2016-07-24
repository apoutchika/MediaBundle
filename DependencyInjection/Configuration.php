<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('apoutchika_media');

        // set default extensions for 
        // default context and trusted extensions
        $defaultExtensions = array(
            // Documents
            'doc', 'xls', 'txt', 'pdf', 'rtf', 'docx', 'xlsx', 'ppt', 'pptx', 'odt', 'odg', 'odp', 'ods', 'odc', 'odf', 'odb', 'csv', 'xml',

            // Images
            'gif', 'jpg', 'jpeg', 'png', 'svg',

            // Audio
            'mp3', 'ogg',

            // Video
            'mp4', 'avi', 'mpg', 'mpeg', 'ogv', 'webm',

            // Archive
            'zip', 'tar', 'gz', '7z', 'rar',
        );

        $originalDir = null;

        $rootNode
            ->children()
                ->scalarNode('media_class')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->validate()
                        ->ifTrue(function ($v) {
                            return !class_exists($v);
                        })
                        ->thenInvalid('The class configured in apoutchika_media.class does not exist')
                    ->end()
                ->end()

                ->arrayNode('trusted_extensions')
                    ->cannotBeEmpty()
                    ->defaultValue($defaultExtensions)
                    ->prototype('scalar')
                        ->cannotBeEmpty()
                        ->validate()
                            ->always()->then(function ($v) {
                                return strtolower($v);
                            })
                        ->end()
                    ->end()
                ->end()

                ->scalarNode('driver')
                    ->cannotBeEmpty()
                    ->defaultValue('gd')
                    ->validate()
                    ->ifNotInArray(array('gd', 'imagick', 'gmagick'))
                        ->thenInvalid('%s is invalid imagine driver: use gd, imagick or gmagick')
                    ->end()
                ->end()

                ->scalarNode('css')
                    ->cannotBeEmpty()
                    ->defaultValue(null)
                ->end()

                ->integerNode('limit')
                    //->cannotBeEmpty()
                    ->defaultValue(null)
                ->end()

                ->scalarNode('original_dir')
                    ->cannotBeEmpty()
                    ->defaultValue('original')
                    ->validate()
                        ->ifTrue(function ($v) use (&$originalDir) {
                            $originalDir = $v;
                            if (preg_match('#^\d*x\d*(_focus)?$#', $v)) {
                                return true;
                            }
                        })
                        ->thenInvalid('The original_dir must not match with "^\d*x\d*(_focus)?$"')
                    ->end()
                ->end()

                ->arrayNode('filesystems')
                    ->children()
                        ->arrayNode('local')
                            ->children()
                                ->scalarNode('path')
                                    ->defaultValue('%kernel.root_dir%/../web/medias/')
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('url')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('url_relative')
                                    ->defaultValue('medias/')
                                    ->cannotBeEmpty()
                                ->end()
                                ->booleanNode('force_absolute_url')
                                    ->defaultFalse()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('ftp')
                            ->children()
                                ->scalarNode('path')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('url')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->integerNode('port')
                                    ->defaultValue(21)
                                    //->cannotBeEmpty()
                                ->end()
                                ->scalarNode('username')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('host')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('password')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->booleanNode('passive')
                                    ->defaultTrue()
                                ->end()
                                ->booleanNode('create')
                                    ->defaultFalse()
                                ->end()
                                ->booleanNode('ssl')
                                    ->defaultFalse()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('include')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('jquery')
                            ->defaultTrue()
                        ->end()
                        ->booleanNode('jqueryui')
                            ->defaultTrue()
                        ->end()
                        ->booleanNode('underscore')
                            ->defaultTrue()
                        ->end()
                        ->booleanNode('backbone')
                            ->defaultTrue()
                        ->end()
                        ->booleanNode('backbonejjrelational')
                            ->defaultTrue()
                        ->end()
                        ->booleanNode('mustache')
                            ->defaultTrue()
                        ->end()
                        ->booleanNode('dropzone')
                            ->defaultTrue()
                        ->end()
                        ->booleanNode('jcrop')
                            ->defaultTrue()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('contexts')
                    ->beforeNormalization()
                        ->ifTrue(function ($v) {
                            return !array_key_exists('default', $v);
                        })
                        ->then(function ($v) use ($defaultExtensions) {
                            $v['default'] = $defaultExtensions;

                            return $v;
                        })
                    ->end()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->prototype('scalar')
                            ->cannotBeEmpty()
                            ->validate()
                                ->always()->then(function ($v) {
                                    return strtolower($v);
                                })
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('alias')
                    ->useAttributeAsKey('name')
                    ->validate()
                        ->ifTrue(function ($v) {
                            foreach ($v as $alias => $values) {
                                if (preg_match('#^\d*x\d*(_focus)?$#', $alias)) {
                                    return true;
                                }
                            }

                            return false;
                        })
                        ->thenInvalid('The alias name must not match with "^\d*x\d*(_focus)?$"')
                    ->end()
                    ->validate()
                        ->ifTrue(function ($v) use (&$originalDir) {

                            return (array_key_exists($originalDir, $v));
                        })
                        ->thenInvalid('The alias "'.$originalDir.'" in apoutchika_media.alias is reserved for original_dir.')
                    ->end()
                    ->prototype('array')
                        ->validate()
                            ->ifTrue(function ($v) {

                                return (empty($v['width']) && empty($v['height']));
                            })
                            ->thenInvalid('The alias must have a width or/and height value')
                        ->end()
                        ->children()
                            ->integerNode('width')
                                ->min(0)
                            ->end()
                            ->integerNode('height')
                                ->min(0)
                            ->end()
                            ->booleanNode('focus')
                                ->defaultTrue()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ;

        return $treeBuilder;
    }
}
