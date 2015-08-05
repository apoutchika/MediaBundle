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
use Gaufrette\Adapter\Local as FtpAdapter;

/**
 * Ftp adapter for filesystem.
 */
class Ftp extends BaseFilesystem
{
    /**
     * Get Adapter.
     *
     * @param array $configs Parameters in filesystems name, injected in gaufrette adapter
     *
     * return \Gaufrette\Adapter\Adapter
     */
    public function getAdapter(array $configs)
    {
        return new FtpAdapter($this->path, $configs['host'], $configs);
    }
}
