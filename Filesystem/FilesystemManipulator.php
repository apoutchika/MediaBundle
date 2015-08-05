<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\Filesystem;

class FilesystemManipulator
{
    /**
     * The master filesystem.
     *
     * @var \Gaufrette\Adapter\Adapter
     */
    private $master = null;

    /**
     * Slaves filesystems.
     *
     * @var array
     */
    private $slaves = array();

    /**
     * Constructor.
     *
     * @param array $filesystems array of filesystems from bundle configuration
     */
    public function __construct(array $filesystems)
    {
        foreach ($filesystems as $type => $configs) {
            $class = '\\Apoutchika\\MediaBundle\\Filesystem\\'.ucfirst($type);

            if (!class_exists($class)) {
                throw new \Exception($type.' class is not exists in ApoutchikaMediaBundle');
            }

            $fs = new $class();
            $fs->setUrl($configs['url']);
            $fs->setPath($configs['path']);

            if (!empty($configs['url_relative'])) {
                $fs->setUrlRelative($configs['url_relative']);
            }

            $fs->makeFilesystem($configs);

            if ($this->master === null) {
                $this->master = $fs;
            } else {
                $this->slaves[] = $fs;
            }
        }
    }

    /**
     * Read the content of the master file.
     *
     * @param string $key Path of file
     *
     * @return string|bool if cannot read content
     */
    public function read($key)
    {
        return $this->master->getFilesystem()->read($key);
    }

    /**
     * Get size of the master file.
     *
     * @param string $key Path of file
     *
     * @return int
     */
    public function size($key)
    {
        return $this->master->getFilesystem()->size($key);
    }

    /**
     * Get url of master file.
     *
     * @param string $key      Path of file
     * @param bool   $absolute (only for local)
     *
     * @return string
     */
    public function url($key, $absolute = false)
    {
        return $this->master->getUrl($absolute).'/'.$key;
    }

    /**
     * Save file.
     *
     * @param string $key Path of file
     * @param string path of file
     */
    public function save($key, $file)
    {
        $this->saveContent($key, file_get_contents($file));
    }

    /**
     * Save file from string, in master and slaves.
     *
     * @param string $key Path of file
     * @param string content of file
     */
    public function saveContent($key, $content)
    {
        $this->master->getFilesystem()->write($key, $content, true);

        foreach ($this->slaves as $slave) {
            $slave->getFilesystem()->write($key, $content, true);
        }
    }

    /**
     * If master has file.
     *
     * @param string $key Path of file
     *
     * @return bool
     */
    public function has($key)
    {
        return $this->master->getFilesystem()->has($key);
    }

    /**
     * delete file in master and slaves.
     *
     * @param string $key Path of file
     */
    public function delete($key)
    {
        $this->master->getFilesystem()->delete($key);

        foreach ($this->slaves as $slave) {
            $slave->getFilesystem()->delete($key);
        }
    }

    /**
     * get all files.
     *
     * @return array
     */
    public function Keys()
    {
        return $this->master->getFilesystem()->keys();
    }

    /**
     * Get path of file.
     *
     * @param string $key Path of file
     *
     * @return string
     */
    public function path($key)
    {
        return $this->master->getPath().'/'.$key;
    }
}
