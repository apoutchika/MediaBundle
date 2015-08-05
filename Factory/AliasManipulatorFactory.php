<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\Factory;

use Apoutchika\MediaBundle\Services\AliasManipulator;

/**
 * Generate class manipulator.
 */
class AliasManipulatorFactory
{
    /**
     * List of alias in array alias => $key.
     *
     * @var array
     */
    private $alias;

    /**
     * Name of original directory.
     *
     * @var string
     */
    private $originalDir;

/**
     * Construction.
     *
     * @param string $originalDir
     * @param array  $alias
     */
    public function __construct($originalDir, array $alias)
    {
        $this->originalDir = $originalDir;
        $this->alias = $alias;
    }

    /**
     * Set alias, and return AliasManipulator.
     *
     * @param array|string|null $alias
     *
     * @return AliasManipulator
     */
    public function setAlias($alias)
    {
        $aliasManipulator = new AliasManipulator($this->originalDir, $this->alias);
        $aliasManipulator->setAlias($alias);

        return $aliasManipulator;
    }
}
