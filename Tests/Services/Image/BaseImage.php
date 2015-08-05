<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\Tests\Services\Image;

use Apoutchika\MediaBundle\Tests\Base;

class BaseImage extends Base
{
    protected $className;

    public function setUp()
    {
        $mockImagine = $this->getMockBuilder('Apoutchika\MediaBundle\Tests\Mocks\Imagine')
                            ->getMock();

        $mockMediaManager = $this->getMockBuilder('Apoutchika\MediaBundle\Manager\MediaManager')
                                ->disableOriginalConstructor()
                                ->getMock();

        $mockMediaManager->expects($this->any())
                         ->method('getImagine')
                         ->will($this->returnValue($mockImagine));

        $mockMediaManager->expects($this->any())
                         ->method('getContent')
                         ->will($this->returnValue('mock'));

        $mockMedia = $this->getMockForAbstractClass('Apoutchika\MediaBundle\Model\Media');

        $this->testClass = new $this->className($mockMediaManager, $mockMedia);

        parent::setup();
    }
}
