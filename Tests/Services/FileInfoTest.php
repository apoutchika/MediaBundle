<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\Tests\Services;

use Apoutchika\MediaBundle\Services\FileInfo;
use Apoutchika\MediaBundle\Model\Media;
use Apoutchika\MediaBundle\Tests\Base;

class FileInfoTest extends Base
{
    public function setUp()
    {
        $this->testClass = new FileInfo();
        parent::setup();
    }

    /**
     * @dataProvider getExtensionProvider
     */
    public function testGetExtension($extension, $filename)
    {
        $splFileInfo = $this->getMockBuilder('\Apoutchika\MediaBundle\Tests\Mocks\File')
            ->setConstructorArgs(array($filename))
            ->getMock();

        $splFileInfo->expects($this->any())
            ->method('getFilename')
            ->will($this->returnValue($filename));

        $splFileInfo->expects($this->any())
            ->method('getClientOriginalName')
            ->will($this->returnValue($filename));

        $this->testClass->setFile($splFileInfo);

        $this->assertEquals($extension, $this->testClass->getExtension());
    }

    /**
     * @dataProvider getTypeProvider
     */
    public function testGetType($type, $filename, $mimeType)
    {
        $splFileInfo = $this->getMockBuilder('\Apoutchika\MediaBundle\Tests\Mocks\File')
            ->setConstructorArgs(array($filename))
            ->getMock();

        $splFileInfo->expects($this->any())
            ->method('getClientOriginalName')
            ->will($this->returnValue($filename));

        $splFileInfo->expects($this->any())
            ->method('getMimeType')
            ->will($this->returnValue($mimeType));

        $splFileInfo->expects($this->any())
            ->method('getClientMimeType')
            ->will($this->returnValue($mimeType));

        $this->testClass->setFile($splFileInfo);

        $this->assertEquals($type, $this->testClass->getType());
    }

    public function getExtensionProvider()
    {
        return array(
            array('jpg', 'test.test.jpg'),
            array('jpg', 'test-test.jpg'),
            array('jpeg', 'te.st.jpeg'),
            array('jpeg', 'test.jpeg'),
            array('mp4', 'test.mp4'),
            array('mp3', 'test.mp3'),
            array('ogg', 'hellotest.ogg'),
            array('ogv', 'hellotest.ogv'),
        );
    }

    public function getTypeProvider()
    {
        return array(
            array(Media::IMAGE, 'test.test.jpg', 'image/jpeg'),
            array(Media::IMAGE, 'test-test.jpg', 'image/jpeg'),
            array(Media::IMAGE, 'te.st.jpeg',    'image/jpeg'),
            array(Media::IMAGE, 'test.jpeg',     'image/jpeg'),

            array(Media::AUDIO, 'test.mp3',      'audio/mp3'),
            array(Media::AUDIO, 'hellotest.ogg', 'audio/ogg'),

            array(Media::VIDEO, 'test.mp4',      'video/mp4'),
            array(Media::VIDEO, 'hellotest.ogg', 'video/ogg'),
            array(Media::VIDEO, 'hellotest.ogv', 'video/ogv'),

            array(Media::OTHER, 'hellotest.csv', 'text/csv'),
        );
    }
}
