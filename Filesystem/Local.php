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

use Gaufrette\Adapter\Local as LocalAdapter;

/**
 * Local adapter for filesystem.
 */
class Local extends BaseFilesystem
{
    /**
     * Set Path.
     *
     * @param string $path
     *
     * @return FilesystemInterface
     */
    public function setPath($path)
    {
        $path = str_replace('/app/../', '/', $path);
        parent::setPath($path);
    }

    /**
     * Get Adapter.
     *
     * @param array $configs Parameters in filesystems name, injected in gaufrette adapter
     *
     * return \Gaufrette\Adapter\Adapter
     */
    public function getAdapter(array $configs)
    {
        if ($configs['force_absolute_url'] === true) {
            $this->urlRelative = $this->urlAbsolute;
        }

        return new LocalAdapter($this->path);
    }
}
