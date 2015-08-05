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

/**
 * Filesystem Interface.
 */
interface FilesystemInterface
{
    /**
     * Make Gaufrette filesystem.
     *
     * @param array $configs Parameters in filesystems name, injected in gaufrette adapter
     *
     * @return FilesystemInterface
     */
    public function makeFilesystem(array $configs);

    /**
     * Set absolute url.
     *
     * @param string $url
     *
     * @return FilesystemInterface
     */
    public function setUrl($url);

    /**
     * Set relative url
     * Only for local filesystem.
     *
     * @param string $url
     *
     * @return FilesystemInterface
     */
    public function setUrlRelative($url);

    /**
     * Set Path.
     *
     * @param string $path
     *
     * @return FilesystemInterface
     */
    public function setPath($path);

    /**
     * Get Path.
     *
     * @return string
     */
    public function getPath();

    /**
     * Get Url.
     *
     * @param bool $absolute (false is only for local)
     *
     * @return string
     */
    public function getUrl($absolute = false);

    /**
     * Get Filesystem.
     *
     * @return \Gaufrette\Filesystem
     */
    public function getFilesystem();

    /**
     * Get Adapter.
     *
     * @param array $configs Parameters in filesystems name, injected in gaufrette adapter
     *
     * return \Gaufrette\Adapter\Adapter
     */
    public function getAdapter(array $configs);
}
