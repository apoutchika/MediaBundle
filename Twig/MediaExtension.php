<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\Twig;

use Apoutchika\MediaBundle\Manager\MediaManager;
use Apoutchika\MediaBundle\Model\MediaInterface;

class MediaExtension extends \Twig_Extension
{
    private $mediaManager;

    public function __construct(MediaManager $mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('media_url', array($this, 'mediaUrlFilter')),
            new \Twig_SimpleFilter('media_abs_url', array($this, 'mediaAbsoluteUrlFilter')),
            new \Twig_SimpleFilter('media_path', array($this, 'mediaPathFilter')),
            new \Twig_SimpleFilter('media_html', array($this, 'mediaHtmlFilter'), array(
                'is_safe' => array('html'),
            )),
        );
    }

    public function mediaHtmlFilter(MediaInterface $media, $alias = null)
    {
        return $this->mediaManager->getHtml($media, $alias);
    }

    public function mediaUrlFilter(MediaInterface $media, $alias = null)
    {
        return $this->mediaManager->getUrl($media, $alias);
    }

    public function mediaAbsoluteUrlFilter(MediaInterface $media, $alias = null)
    {
        return $this->mediaManager->getAbsoluteUrl($media, $alias);
    }

    public function mediaPathFilter(MediaInterface $media, $alias = null)
    {
        return $this->mediaManager->getPath($media, $alias);
    }

    public function getName()
    {
        return 'apoutchika_media_extension';
    }
}
