<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * class CKEditorController.
 */
class CKEditorController extends Controller
{
    /**
     * Show list interface (with add and edit) for ckeditor.
     *
     * @param Request $request
     */
    public function imageAction(Request $request)
    {
        return $this->render('ApoutchikaMediaBundle:CKEditor:image.html.twig', array(
            'filter' => $this->get('apoutchika_media.filter')->set(null),
        ));
    }
}
