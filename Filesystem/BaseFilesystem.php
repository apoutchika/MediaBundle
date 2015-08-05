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

use Gaufrette\Filesystem;
use Gaufrette\Adapter;

/**
 * Base Filesystem.
 */
abstract class BaseFilesystem implements FilesystemInterface
{
    /**
     * Path of root media directory.
     *
     * @var string
     */
    protected $path;

    /**
     * Absolute url of root media directory.
     *
     * @var string
     */
    protected $urlAbsolute;

    /**
     * Relative url of root media directory
     * Only for local filesystem.
     *
     * @var string
     */
    protected $urlRelative;

    /**
     * Gaufrette Filesystem.
     *
     * @var \Gaufrette\Filesystem
     */
    protected $filesystem;

    /**
     * Make Gaufrette filesystem.
     *
     * @param array $configs Parameters in filesystems name, injected in gaufrette adapter
     *
     * @return FilesystemInterface
     */
    public function makeFilesystem(array $configs)
    {
        $adapter = $this->getAdapter($configs);

        if (!$adapter instanceof Adapter) {
            throw new \Exception('The adapter must has instance of Gaufrette\Adapter');
        }

        $this->filesystem = new Filesystem($adapter);

        return $this;
    }

    /**
     * Set absolute url.
     *
     * @param string $url
     *
     * @return FilesystemInterface
     */
    public function setUrl($url)
    {
        $this->urlAbsolute = preg_replace('#/$#', '', $url);

        if ($this->urlRelative === null) {
            $this->urlRelative = $this->urlAbsolute;
        }

        return $this;
    }

    /**
     * Set relative url
     * Only for local filesystem.
     *
     * @param string $url
     *
     * @return FilesystemInterface
     */
    public function setUrlRelative($url)
    {
        $this->urlRelative = preg_replace('#/$#', '', $url);
    }

    /**
     * Get Url.
     *
     * @param bool $absolute (false is only for local)
     *
     * @return string
     */
    public function getUrl($absolute = false)
    {
        if ($absolute === true) {
            return $this->urlAbsolute;
        } else {
            return $this->urlRelative;
        }
    }

    /**
     * Set Path.
     *
     * @param string $path
     *
     * @return FilesystemInterface
     */
    public function setPath($path)
    {
        $this->path = preg_replace('#/$#', '', $path);

        return $this;
    }

    /**
     * Get Path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get Filesystem.
     *
     * @return \Gaufrette\Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }
}
