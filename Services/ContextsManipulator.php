<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\Services;

/**
 * Contexts manipulator.
 *
 * Manage contexts and allowed extensions
 */
class ContextsManipulator
{
    /**
     * @var array
     */
    private $contexts = array();

    /**
     * @var array
     */
    private $allowedExtensions = array();

    /**
     * @param array $contexts
     */
    public function __construct(array $contexts)
    {
        $this->contexts = $contexts;
    }

    /**
     * Insert contexts extensions in allowed extensions.
     *
     * @param string $name context name
     */
    private function insertContextInAllowedExtensions($name)
    {
        if (!array_key_exists($name, $this->contexts)) {
            throw new \Exception('The context '.$name.' is not defined.');
        }

        $this->allowedExtensions = array_merge($this->contexts[$name], $this->allowedExtensions);
    }

    /**
     * Add other context.
     *
     * @param array|string $contexts
     *
     * @return ContextsManipulator
     */
    public function addContexts($contexts)
    {
        if (is_array($contexts)) {
            foreach ($contexts as $context) {
                $this->insertContextInAllowedExtensions($context);
            }
        } elseif ($contexts !== false) {
            $this->insertContextInAllowedExtensions($contexts);
        }

        return $this;
    }

    /**
     * Add other allowed extensions.
     *
     * @param array $extensions
     *
     * @return ContextsManipulator
     */
    public function addAllowedExtensions(array $extensions)
    {
        $this->allowedExtensions = array_merge($this->allowedExtensions, $extensions);

        return $this;
    }

    /**
     * Get allowed extensions.
     *
     * @return array
     */
    public function getAllowedExtensions()
    {
        if (empty($this->allowedExtensions)) {
            $this->addContexts('default');
        }

        return array_unique($this->allowedExtensions);
    }
}
