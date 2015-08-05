<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Templating\Helper\CoreAssetsHelper;
use Symfony\Bundle\TwigBundle\TwigEngine;

/**
 * MediaContentListener.
 *
 * Detect if the response has media field, and add css, js and templates for mustaches
 */
class MediaContentListener
{
    /**
     * @var CoreAssetsHelper
     */
    protected $assetsHelper;

    /**
     * @var TwigEngine
     */
    protected $templating;

    /**
     * @var array List of js dependances from bundle configuration
     */
    private $include;

    /**
     * @var string Custom css file
     */
    private $css;

    /**
     * @param array  $include List of js dependances from bundle configuration
     * @param string $css     Custom css file
     */
    public function __construct($include, $css)
    {
        $this->include = $include;
        $this->css = $css;
    }

    /**
     * @param CoreAssetsHelper $assetsHelper
     */
    public function setAssetsHelper(CoreAssetsHelper $assetsHelper)
    {
        $this->assetsHelper = $assetsHelper;
    }

    /**
     * @param TwigEngine $templating
     */
    public function setTemplating(TwigEngine $templating)
    {
        $this->templating = $templating;
    }

    /**
     * Detect if the response has media field in her content,
     * and add css, js and templates for mustaches.
     *
     * @param FilterResponseEvent $event
     *
     * @return FilterResponseEvent
     */
    public function addContent(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $response = $event->getResponse();
        $content = $response->getContent();

        if (strpos($content, 'data-apoutchika-media="') !== false) {
            $js = array();

            // Include libs
            $libs = array(
                'jquery', 'jqueryui',
                'underscore', 'backbone', 'backbonejjrelational', 'mustache',
                'dropzone', 'jcrop',
            );

            foreach ($libs as $lib) {
                if ($this->include[$lib] === true) {
                    $js[] = 'bundles/apoutchikamedia/js/libs/'.$lib.'.js';
                }
            }

            // Include apps
            $apps = array(
                'init',
                'models', 'collections',
                'viewAdd', 'viewEditor', 'viewField', 'viewList',
                'router', 'app',
            );

            foreach ($apps as $app) {
                $js[] = 'bundles/apoutchikamedia/js/'.$app.'.js';
            }

            $content_js = '';
            foreach ($js as $n) {
                $content_js .= '<script src="';
                $content_js .= $this->assetsHelper->getUrl($n);
                $content_js .= '" type="text/javascript"></script>';
            }

            $css = array();

            $css[] = ($this->css !== null) ?
                    $this->css : 'bundles/apoutchikamedia/css/main.css';

            if ($this->include['jcrop'] === true) {
                $css[] = 'bundles/apoutchikamedia/css/jcrop.css';
            }

            $content_css = '';
            foreach ($css as $n) {
                $content_css .= '<link rel="stylesheet" type="text/css"  href="';
                $content_css .= $this->assetsHelper->getUrl($n);
                $content_css .= '" />';
            }

            $html = $this->templating->render('ApoutchikaMediaBundle:Media:media.html.twig');

            $content = preg_replace('#</head>#', $content_css.'</head>', $content);
            $content = preg_replace('#</body>#', $html.$content_js.'</body>', $content);

            $response->setContent($content);
            $event->setResponse($response);
        }
    }
}
