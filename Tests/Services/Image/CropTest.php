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

class CropTest extends BaseImage
{
    protected $className = '\Apoutchika\MediaBundle\Services\Image\Crop';

    /**
     * @dataProvider getUpdateFocusProvider
     */
    public function testUpdateFocus($x, $y, $oldLeft, $oldTop, $newLeft, $newTop)
    {
        $media = $this->getMockForAbstractClass('Apoutchika\MediaBundle\Model\Media');

        $media->setWidth(100);
        $media->setHeight(100);
        $media->setFocusLeft($oldLeft);
        $media->setFocusTop($oldTop);

        $this->testClass->setSize($x, $y, 40, 40);

        $this->testClass->updateFocus($media);

        $this->assertEquals($newLeft, $media->getFocusLeft());
        $this->assertEquals($newTop, $media->getFocusTop());
    }

    public function getUpdateFocusProvider()
    {
        return array(

            // test on top left
            array(0, 0, 0, 0, 0, 0),
            array(0, 0, 10, 0, 25, 0),
            array(0, 0, 20, 0, 50, 0),
            array(0, 0, 30, 0, 75, 0),
            array(0, 0, 40, 0, 100, 0),

            array(0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 10, 0, 25),
            array(0, 0, 0, 20, 0, 50),
            array(0, 0, 0, 30, 0, 75),
            array(0, 0, 0, 40, 0, 100),

            array(0, 0, 20, 20, 50, 50),
            array(0, 0, 30, 30, 75, 75),
            array(0, 0, 40, 40, 100, 100),

            array(20, 40, 30, 20, 25, 0),
            array(20, 40, 90, 70, 100, 75),
            array(20, 40, 30, 90, 25, 100),
            array(20, 40, 10, 70, 0, 75),

            // test on bottom right

            array(60, 60, 60, 60, 0, 0),
            array(60, 60, 70, 90, 25, 75),
            array(60, 60, 90, 80, 75, 50),
            array(60, 60, 90, 90, 75, 75),
            array(60, 60, 100, 100, 100, 100),

            // Full test on middle

            array(20, 40, 10, 30, 0, 0),
            array(20, 40, 20, 30, 0, 0),
            array(20, 40, 30, 30, 25, 0),
            array(20, 40, 40, 30, 50, 0),
            array(20, 40, 50, 30, 75, 0),
            array(20, 40, 60, 30, 100, 0),
            array(20, 40, 70, 30, 100, 0),

            array(20, 40, 10, 40, 0, 0),
            array(20, 40, 20, 40, 0, 0),
            array(20, 40, 30, 40, 25, 0),
            array(20, 40, 40, 40, 50, 0),
            array(20, 40, 50, 40, 75, 0),
            array(20, 40, 60, 40, 100, 0),
            array(20, 40, 70, 40, 100, 0),

            array(20, 40, 10, 50, 0, 25),
            array(20, 40, 20, 50, 0, 25),
            array(20, 40, 30, 50, 25, 25),
            array(20, 40, 40, 50, 50, 25),
            array(20, 40, 50, 50, 75, 25),
            array(20, 40, 60, 50, 100, 25),
            array(20, 40, 70, 50, 100, 25),

            array(20, 40, 10, 60, 0, 50),
            array(20, 40, 20, 60, 0, 50),
            array(20, 40, 30, 60, 25, 50),
            array(20, 40, 40, 60, 50, 50),
            array(20, 40, 50, 60, 75, 50),
            array(20, 40, 60, 60, 100, 50),
            array(20, 40, 70, 60, 100, 50),

            array(20, 40, 10, 70, 0, 75),
            array(20, 40, 20, 70, 0, 75),
            array(20, 40, 30, 70, 25, 75),
            array(20, 40, 40, 70, 50, 75),
            array(20, 40, 50, 70, 75, 75),
            array(20, 40, 60, 70, 100, 75),
            array(20, 40, 70, 70, 100, 75),

            array(20, 40, 10, 80, 0, 100),
            array(20, 40, 20, 80, 0, 100),
            array(20, 40, 30, 80, 25, 100),
            array(20, 40, 40, 80, 50, 100),
            array(20, 40, 50, 80, 75, 100),
            array(20, 40, 60, 80, 100, 100),
            array(20, 40, 70, 80, 100, 100),

            array(20, 40, 10, 90, 0, 100),
            array(20, 40, 20, 90, 0, 100),
            array(20, 40, 30, 90, 25, 100),
            array(20, 40, 40, 90, 50, 100),
            array(20, 40, 50, 90, 75, 100),
            array(20, 40, 60, 90, 100, 100),
            array(20, 40, 70, 90, 100, 100),
        );
    }
}
