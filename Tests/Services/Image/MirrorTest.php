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

class MirrorTest extends BaseImage
{
    protected $className = 'Apoutchika\MediaBundle\Services\Image\Mirror';

    /**
     * @dataProvider getUpdateFocusProvider
     */
    public function testUpdateFocus($direction, $fromLeft, $fromTop, $toLeft, $toTop)
    {
        $media = new Media();
        $media
            ->setFocusLeft($fromLeft)
            ->setFocusTop($fromTop)
            ;

        $this
            ->testClass
            ->setDirection($direction)
            ->updateFocus($media)
            ;

        $this->assertEquals($media->getFocusLeft(), $toLeft);
        $this->assertEquals($media->getFocusTop(), $toTop);
    }

    public function getUpdateFocusProvider()
    {
        return array(
            array('x', 0,   0, 100, 0),
            array('x', 25,  0, 75,  0),
            array('x', 50,  0, 50,  0),
            array('x', 75,  0, 25,  0),
            array('x', 100, 0, 0,   0),

            array('x', 0,   25, 100, 25),
            array('x', 25,  25, 75,  25),
            array('x', 50,  25, 50,  25),
            array('x', 75,  25, 25,  25),
            array('x', 100, 25, 0,   25),

            array('x', 0,   50, 100, 50),
            array('x', 25,  50, 75,  50),
            array('x', 50,  50, 50,  50),
            array('x', 75,  50, 25,  50),
            array('x', 100, 50, 0,   50),

            array('x', 0,   75, 100, 75),
            array('x', 25,  75, 75,  75),
            array('x', 50,  75, 50,  75),
            array('x', 75,  75, 25,  75),
            array('x', 100, 75, 0,   75),

            array('x', 0,   100, 100, 100),
            array('x', 25,  100, 75,  100),
            array('x', 50,  100, 50,  100),
            array('x', 75,  100, 25,  100),
            array('x', 100, 100, 0,   100),

            array('y', 0, 0,   0, 100),
            array('y', 0, 25,  0,  75),
            array('y', 0, 50,  0,  50),
            array('y', 0, 75,  0,  25),
            array('y', 0, 100, 0,   0),

            array('y', 25, 0,   25, 100),
            array('y', 25, 25,  25,  75),
            array('y', 25, 50,  25,  50),
            array('y', 25, 75,  25,  25),
            array('y', 25, 100, 25,   0),

            array('y', 50, 0,   50, 100),
            array('y', 50, 25,  50,  75),
            array('y', 50, 50,  50,  50),
            array('y', 50, 75,  50,  25),
            array('y', 50, 100, 50,   0),

            array('y', 50, 0,   50, 100),
            array('y', 50, 25,  50,  75),
            array('y', 50, 50,  50,  50),
            array('y', 50, 75,  50,  25),
            array('y', 50, 100, 50,   0),

            array('y', 100, 0,   100, 100),
            array('y', 100, 25,  100,  75),
            array('y', 100, 50,  100,  50),
            array('y', 100, 75,  100,  25),
            array('y', 100, 100, 100,   0),
        );
    }
}
