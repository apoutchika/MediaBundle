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

class LocalTest extends  \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getLocalProvider
     */
    public function testLocal($configs, $response)
    {
        $fs = new FilesystemManipulator(array('local' => $configs));

        $this->assertEquals($response['path'], $fs->path($response['key']));
        $this->assertEquals($response['url_relative'], $fs->url($response['key']));
        $this->assertEquals($response['url'], $fs->url($response['key'], true));
    }

    public function getLocalProvider()
    {
        return array(

            array(
                array(
                    'url' => 'http://www.exemple.tld/medias',
                    'url_relative' => 'medias',
                    'path' => '/var/www/test/app/../web/medias',
                    'force_absolute_url' => false,
                ), array(
                    'key' => 'original/test.png',
                    'path' => '/var/www/test/web/medias/original/test.png',
                    'url' => 'http://www.exemple.tld/medias/original/test.png',
                    'url_relative' => 'medias/original/test.png',
                ),
            ),

            array(
                array(
                    'url' => 'http://www.exemple.tld/medias/',
                    'url_relative' => 'medias/',
                    'path' => '/var/www/test/app/../web/medias/',
                    'force_absolute_url' => false,
                ), array(
                    'key' => 'original/test.png',
                    'path' => '/var/www/test/web/medias/original/test.png',
                    'url' => 'http://www.exemple.tld/medias/original/test.png',
                    'url_relative' => 'medias/original/test.png',
                ),
            ),

            array(
                array(
                    'url' => 'http://www.exemple.tld/medias/',
                    'path' => '/var/www/test/app/../web/medias/',
                    'force_absolute_url' => false,
                ), array(
                    'key' => 'original/test.png',
                    'path' => '/var/www/test/web/medias/original/test.png',
                    'url' => 'http://www.exemple.tld/medias/original/test.png',
                    'url_relative' => 'http://www.exemple.tld/medias/original/test.png',
                ),
            ),

            array(
                array(
                    'url' => 'http://www.exemple.tld/medias/',
                    'url_relative' => 'medias/',
                    'path' => '/var/www/test/app/../web/medias/',
                    'force_absolute_url' => true,
                ), array(
                    'key' => 'original/test.png',
                    'path' => '/var/www/test/web/medias/original/test.png',
                    'url' => 'http://www.exemple.tld/medias/original/test.png',
                    'url_relative' => 'http://www.exemple.tld/medias/original/test.png',
                ),
            ),

        );
    }
}
