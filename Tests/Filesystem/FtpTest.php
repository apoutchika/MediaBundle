<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\Tests\Filesystem;

use Apoutchika\MediaBundle\Filesystem\FilesystemManipulator;

class FtpTest extends  \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getLocalProvider
     */
    public function testLocal($configs, $response)
    {
        $fs = new FilesystemManipulator(array('ftp' => $configs));

        $this->assertEquals($response['path'], $fs->path($response['key']));
        $this->assertEquals($response['url'], $fs->url($response['key'], true));
    }

    public function getLocalProvider()
    {
        return array(

            array(
                array(
                    'url' => 'http://www.exemple.tld/medias',
                    'path' => '/web/medias',
                    'host' => 'ftp.exemple.tld',
                ), array(
                    'key' => 'original/test.png',
                    'path' => '/web/medias/original/test.png',
                    'url' => 'http://www.exemple.tld/medias/original/test.png',
                    'url_relative' => 'http://www.exemple.tld/medias/original/test.png',
                ),
            ),

            array(
                array(
                    'url' => 'http://www.exemple.tld/medias/',
                    'path' => '/web/medias/',
                    'host' => 'ftp.exemple.tld',
                ), array(
                    'key' => 'original/test.png',
                    'path' => '/web/medias/original/test.png',
                    'url' => 'http://www.exemple.tld/medias/original/test.png',
                    'url_relative' => 'http://www.exemple.tld/medias/original/test.png',
                ),
            ),

        );
    }
}
