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

class ResizeTest extends BaseImage
{
    protected $className = 'Apoutchika\MediaBundle\Services\Image\Resize';

    /**
     * @dataProvider getTargetSizeProvider
     */
    public function testTargetSize($ow, $oh, $tw, $th, $rw, $rh)
    {
        $method = $this->getMethod('getTargetSize');
        $this->assertEquals(
            array('width' => $rw, 'height' => $rh),
            $method->invoke($this->testClass, $ow, $oh, $tw, $th)
        );
    }

    public function getTargetSizeProvider()
    {
        return array(

            // original >= target
            array(1000, 1000, 100,  100,  100, 100),
            array(1000, 1000, 100,  50,   50 , 50),
            array(1000, 1000, 50,   100,  50,  50),
            array(1000, 1000, null, 100,  100, 100),
            array(1000, 1000, 100,  null, 100, 100),

            array(1000, 500, 100,  100,  100, 50),
            array(1000, 500, 100,  50,   100, 50),
            array(1000, 500, 50,   100,  50,  25),
            array(1000, 500, null, 100,  200, 100),
            array(1000, 500, 100,  null, 100, 50),

            array(500, 1000, 100,  100,  50,  100),
            array(500, 1000, 100,  50,   25,  50),
            array(500, 1000, 50,   100,  50,  100),
            array(500, 1000, null, 100,  50,  100),
            array(500, 1000, 100,  null, 100, 200),

            // original < target
            array(10, 10, 100,  100,  10, 10),
            array(10, 10, 100,  50,   10, 10),
            array(10, 10, 50,   100,  10, 10),
            array(10, 10, null, 100,  10, 10),
            array(10, 10, 100,  null, 10, 10),

            array(10, 5, 100,  100,  10, 5),
            array(10, 5, 100,  50,   10, 5),
            array(10, 5, 50,   100,  10, 5),
            array(10, 5, null, 100,  10, 5),
            array(10, 5, 100,  null, 10, 5),

            array(5, 10, 100,  100,  5, 10),
            array(5, 10, 100,  50,   5, 10),
            array(5, 10, 50,   100,  5, 10),
            array(5, 10, null, 100,  5, 10),
            array(5, 10, 100,  null, 5, 10),

            // original width < target width &&
            // original height > target height
            array(20, 500, 100,  100,  4, 100),
            array(20, 500, 100,  50,   2, 50),
            array(20, 500, 50,   100,  4, 100),
            array(20, 500, null, 100,  4, 100),
            array(20, 500, 100,  null, 20, 500),

            // original width > target width &&
            // original height < target height
            array(500, 20, 100,  100,  100, 4),
            array(500, 20, 100,  50,   100, 4),
            array(500, 20, 50,   100,  50,  2),
            array(500, 20, null, 100,  500, 20),
            array(500, 20, 100,  null, 100, 4),
        );
    }
}
