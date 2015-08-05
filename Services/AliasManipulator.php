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
 * AliasManipulator.
 *
 * Manage alias :
 *      - name ('200x300_focus') 
 *      - array (array('width' => 200, 'height' => 300, 'focus' => true);
 *      - in alias config (header: 800x300_focus)
 *
 *  Send one of this type and get alias or array of this
 */
class AliasManipulator
{
    /**
     * @var string
     */
    private $originalDir;

    /**
     * @var alias
     */
    private $alias;

    /**
     * @var string
     */
    private $aliasName;

    /**
     * @var array
     */
    private $aliasArray;

/**
     * @param string $originalDir
     * @param array  $alias
     */
    public function __construct($originalDir, array $alias)
    {
        $this->originalDir = $originalDir;
        $this->alias = $alias;
    }

    /**
     * @param string|array $alias
     *
     * @return AliasManipulator
     */
    public function setAlias($alias)
    {
        if (is_array($alias)) {
            $this->aliasArray = $this->valideAliasArray($alias);
            $this->aliasName = $this->aliasArrayToName($this->aliasArray);
        } else {
            $this->aliasName = $this->valideAliasName($alias);
            $this->aliasArray = $this->aliasNameToArray($this->aliasName);
        }

        return $this;
    }

    /**
     * Get alias name (ex: '200x300_focus').
     *
     * @return string
     */
    public function getAliasName()
    {
        return $this->aliasName;
    }

    /**
     * Get alias array (ex: array('width' => 200, 'height' => 300, 'focus' => true);).
     *
     * @return array
     */
    public function getAliasArray()
    {
        return $this->aliasArray;
    }

    /**
     * Verify if the alias name is valid.
     *
     * @param string $aliasName
     *
     * @return string (alias name);
     */
    public function valideAliasName($aliasName)
    {
        if ($aliasName === null || $aliasName === $this->originalDir) {
            return $this->originalDir;
        }

        if (array_key_exists($aliasName, $this->alias)) {
            return $this->aliasArrayToName($this->alias[$aliasName]);
        }

        if (!preg_match('#^(\d*)x(\d*)(_focus)?$#', $aliasName)) {
            throw new \Exception($aliasName.' is not valid alias name');
        }

        if (preg_match('#^(x\d+|\d+x)_focus$#', $aliasName)) {
            return str_replace('_focus', '', $aliasName);
        }

        return $aliasName;
    }

    /**
     * Verify alias array.
     *
     * @param array $aliasArray
     *
     * @return array ($aliasArray)
     */
    public function valideAliasArray($aliasArray)
    {
        $aliasArray = array_replace(array('width' => null, 'height' => null), $aliasArray);

        foreach ($aliasArray as $name => $value) {
            if (!in_array($name, array('width', 'height', 'focus'))) {
                throw new \Exception($name.' is not a good key');
            }

            if (in_array($name, array('width', 'height')) && (!preg_match('#^\d+$#', $value) && $value !== null)) {
                throw new \Exception($value.' must be a integer or null');
            }

            if ($name === 'focus' && !is_bool($value)) {
                throw new \Exception($value.' must be a boolean');
            }
        }

        if ($aliasArray['width'] === null || $aliasArray['height'] === null) {
            $aliasArray['focus'] = false;
        } elseif (!isset($aliasArray['focus']) && $aliasArray['width'] !== null && $aliasArray['height'] !== null) {
            $aliasArray['focus'] = true;
        }

        return $aliasArray;
    }

    /**
     * Transform aliass array to name.
     *
     * @param array $aliasArray
     *
     * @return string
     */
    private function aliasArrayToName(array $aliasArray)
    {
        if ($aliasArray === null || (empty($aliasArray['width']) && empty($aliasArray['height']))) {
            return $this->originalDir;
        }

        if (!empty($aliasArray['focus'])) {
            if (!in_array($aliasArray['focus'], array(true, false))) {
                throw new \Exception('Focus must be a boolean type');
            }

            $end = ($aliasArray['focus'] === true) ? '_focus' : '';
        } else {
            $end = '';
        }

        if (!empty($aliasArray['width']) && !empty($aliasArray['height'])) {
            return $aliasArray['width'].'x'.$aliasArray['height'].$end;
        }

        if (!empty($aliasArray['width'])) {
            return $aliasArray['width'].'x';
        }

        if (!empty($aliasArray['height'])) {
            return 'x'.$aliasArray['height'];
        }
    }

    /**
     * Transform alias name to array.
     * 
     * @param string $aliasName
     *
     * @return array
     */
    private function aliasNameToArray($aliasName)
    {
        if (!empty($aliasName) && isset($this->alias[$aliasName])) {
            $aliasName = $this->aliasArrayToName($this->alias[$aliasName]);
        } elseif (is_array($aliasName)) {
            $aliasName = $this->aliasArrayToName($aliasName);
        }

        $aliasArray = array();

        $width = preg_replace('#^(\d*)x.*$#', '$1', $aliasName);
        $aliasArray['width'] = (!empty($width)) ? intval($width) : null;

        $height = preg_replace('#^\d*x(\d*)(_focus)?$#', '$1', $aliasName);
        $aliasArray['height'] = (!empty($height)) ? intval($height) : null;

        if ($aliasArray['width'] !== null && $aliasArray['height'] !== null) {
            $aliasArray['focus'] = (preg_match('#_focus$#', $aliasName)) ? true : false;
        } else {
            $aliasArray['focus'] = false;
        }

        return $aliasArray;
    }
}
