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

use Apoutchika\MediaBundle\Tests\Mocks\Media;

class RotateTest extends BaseImage
{
    protected $className = 'Apoutchika\MediaBundle\Services\Image\Rotate';

    /**
     * @dataProvider getUpdateFocusProvider
     */
    public function testUpdateFocus($angle, $fromLeft, $fromTop, $toLeft, $toTop)
    {
        $media = new Media();
        $media
            ->setFocusLeft($fromLeft)
            ->setFocusTop($fromTop)
            ;

        $this
            ->testClass
            ->setAngle($angle)
            ->updateFocus($media)
            ;

        $this->assertEquals($media->getFocusLeft(), $toLeft);
        $this->assertEquals($media->getFocusTop(), $toTop);
    }

    public function getUpdateFocusProvider()
    {
        return array(
            array(90, 0,   0, 100, 0),
            array(90, 25,  0, 100, 25),
            array(90, 50,  0, 100, 50),
            array(90, 75,  0, 100, 75),
            array(90, 100, 0, 100, 100),

            array(90, 0,   25, 75, 0),
            array(90, 25,  25, 75, 25),
            array(90, 50,  25, 75, 50),
            array(90, 75,  25, 75, 75),
            array(90, 100, 25, 75, 100),

            array(90, 0,   50, 50, 0),
            array(90, 25,  50, 50, 25),
            array(90, 50,  50, 50, 50),
            array(90, 75,  50, 50, 75),
            array(90, 100, 50, 50, 100),

            array(90, 0,   75, 25, 0),
            array(90, 25,  75, 25, 25),
            array(90, 50,  75, 25, 50),
            array(90, 75,  75, 25, 75),
            array(90, 100, 75, 25, 100),

            array(90, 0,   100, 0, 0),
            array(90, 25,  100, 0, 25),
            array(90, 50,  100, 0, 50),
            array(90, 75,  100, 0, 75),
            array(90, 100, 100, 0, 100),

            array(-90, 0,   0,  0, 100),
            array(-90, 25,  0,  0,  75),
            array(-90, 50,  0,  0,  50),
            array(-90, 75,  0,  0,  25),
            array(-90, 100, 0,  0,   0),

            array(-90, 0,   25,  25, 100),
            array(-90, 25,  25,  25,  75),
            array(-90, 50,  25,  25,  50),
            array(-90, 75,  25,  25,  25),
            array(-90, 100, 25,  25,   0),

            array(-90, 0,   50,  50, 100),
            array(-90, 25,  50,  50,  75),
            array(-90, 50,  50,  50,  50),
            array(-90, 75,  50,  50,  25),
            array(-90, 100, 50,  50,   0),

            array(-90, 0,   75,  75, 100),
            array(-90, 25,  75,  75,  75),
            array(-90, 50,  75,  75,  50),
            array(-90, 75,  75,  75,  25),
            array(-90, 100, 75,  75,   0),

            array(-90, 0,   100,  100, 100),
            array(-90, 25,  100,  100,  75),
            array(-90, 50,  100,  100,  50),
            array(-90, 75,  100,  100,  25),
            array(-90, 100, 100,  100,   0),
        );
    }
}
