<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\Factory;

use Imagine\Imagick\Imagine as ImagineImagick;
use Imagine\Gmagick\Imagine as ImagineGmagick;
use Imagine\Gd\Imagine as ImagineGd;
use Imagine\Image\ImagineInterface;

/**
 * Generate Imagine with prefered driver.
 */
class ImagineFactory
{
    /**
     * Get Image class.
     *
     * @param string $driver gd|imagick|gmagick
     *
     * @return ImagineInterface
     */
    public static function get($driver)
    {
        if ($driver === 'imagick') {
            return new ImagineImagick();
        } elseif ($driver === 'gmagick') {
            return new ImagineGmagick();
        }

        return new ImagineGd();
    }
}
