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

class FocusTest extends BaseImage
{
    protected $className = 'Apoutchika\MediaBundle\Services\Image\Focus';

    /**
     * @dataProvider getStartCropProvider
     */
    public function testStartCrop($origin, $target, $focus, $result)
    {
        $this->assertEquals(
            $result,
            $this->testClass->getStartCrop($origin, $target, $focus)
        );
    }

    public function getStartCropProvider()
    {
        return array(
            array(100, 40, 0, 0),
            array(100, 40, 10, 0),
            array(100, 40, 20, 0),
            array(100, 40, 30, 10),
            array(100, 40, 40, 20),
            array(100, 40, 50, 30),
            array(100, 40, 60, 40),
            array(100, 40, 70, 50),
            array(100, 40, 80, 60),
            array(100, 40, 90, 60),
            array(100, 40, 100, 60),
        );
    }
}
