<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\Tests\Manager;

use Apoutchika\MediaBundle\Manager\MediaManager;
use Apoutchika\MediaBundle\Tests\Base;
use Apoutchika\MediaBundle\Tests\Mocks\Media;

class MediaManagerTest extends Base
{
    public function setUp()
    {
        $this->testClass = new MediaManager(
            'original/',  // original dir
            array( // contexts
                'default' => array('jpg', 'jpeg', 'png', 'doc', 'txt', 'mp3'),
                'musique' => array('mp3'),
                'pdf' => array('pdf'),
                'app' => array('exe', 'sh'),
            ),
            array( // trusted extensiosn
                'jpg', 'jpeg', 'png', 'doc', 'txt', 'mp3', 'pdf',
            ),
            1000 // limit
        );

        $this->testClass->setClass('Apoutchika\MediaBundle\Tests\Entity\Media');

        parent::setup();

        $fsm = $this->getMockBuilder('Apoutchika\MediaBundle\Filesystem\FilesystemManipulator')
            ->disableOriginalConstructor()
            ->getMock();

        $fsm
            ->expects($this->any())
            ->method('has')
            ->will($this->returnValue(false))
            ;

        $this->setProperty('filesystemManipulator', $fsm);
    }

    public function testFailCreateReference()
    {
        $media = new Media();
        $media->setExtension('fail');

        $mm = $this->testClass;
        $this->assertFalse($mm->createReference($media));
    }

    /**
     * @dataProvider getCreateReferenceProvider
     */
    public function testCreateReference($extension, $savedExtension)
    {
        $media = new Media();
        $media->setExtension($extension);

        $mm = $this->testClass;
        $mm->createReference($media);
        $this->assertRegExp(
            '#^[a-z0-9]+\.'.str_replace('.', '\.', $savedExtension).'$#',
            $media->getReference());
    }

    public function getCreateReferenceProvider()
    {
        return array(
            array('png', 'png'),
            array('mp3', 'mp3'),
            array('pdf', 'pdf'),
            array('txt', 'txt'),
            array('exe', 'exe.txt'),
            array('sh', 'sh.txt'),
        );
    }
}
