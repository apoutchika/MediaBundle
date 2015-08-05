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
use Symfony\Component\HttpFoundation\Response;
use Apoutchika\MediaBundle\Model\MediaInterface;
use Apoutchika\MediaBundle\Services\Image\Rotate;
use Apoutchika\MediaBundle\Services\Image\Mirror;
use Apoutchika\MediaBundle\Services\Image\Crop;
use JMS\Serializer\SerializerBuilder;

/**
 * REST controller.
 *
 * class MediaRestController
 */
class MediaRestController extends Controller
{
    /**
     * Get mediaMamanger.
     *
     * @return \Apoutchika\MediaBundle\Manager\MediaManager
     */
    private function getMediaManager()
    {
        return $this->get('apoutchika_media.manager.media');
    }

    /**
     * Add infos for front : urls of thumbnails and html value of media.
     *
     * @param MediaInterface $media
     *
     * @return MediaInterface
     */
    private function addInfosForFront(MediaInterface $media)
    {
        $mediaManager = $this->getMediaManager();

        $media->setUrls(array(
            'original' => $mediaManager->getUrl($media),
            '500x400' => $mediaManager->getUrl($media, '500x400'),
            '78x44_focus' => $mediaManager->getUrl($media, '78x44_focus'),
        ));

        $media->setHtml(
            $mediaManager->getHtml($media)
        );

        return $media;
    }

    /**
     * Create response.
     *
     * @param null|array|MediaInterface $data
     * @param bool                      $iframe
     *
     * @return Response
     */
    private function createResponse($data, $iframe = false)
    {
        $serializer = SerializerBuilder::create()->build();

        if (is_array($data)) {
            foreach ($data as $k => $n) {
                if ($n instanceof MediaInterface) {
                    $data[$k] = $this->addInfosForFront($n);
                }
            }
        } else {
            if ($data instanceof MediaInterface) {
                $data = $this->addInfosForFront($data);
            }
        }

        if ($iframe) {
            return new Response(htmlentities($serializer->serialize($data, 'json')));
        } else {
            return new Response($serializer->serialize($data, 'json'));
        }
    }

    /**
     * Get all medias.
     *
     * @param Request $request
     *
     * @return Response array of medias
     */
    public function getMediasAction(Request $request)
    {
        if (!$request->query->has('filters')) {
            return $this->createResponse(array());
        }

        $mediaManager = $this->getMediaManager();

        $filtersKeys = explode(',', $request->query->get('filters'));
        $medias = $mediaManager->findAllByFiltersKeys($filtersKeys);

        return $this->createResponse($medias);
    }

    /**
     * Get media.
     *
     * @param Request $request
     * @param int     $id      id of media
     *
     * @return Response media
     */
    public function getMediaAction(Request $request, $id)
    {
        $response = $this->getFilter($request);
        if ($response['success'] === false) {
            return $response['value'];
        }
        $filter = $response['value'];

        $mm = $this->getMediaManager();

        $media = $mm->findOneBy(array(
            'id' => $id,
            'filter' => $filter,
        ));

        if ($media !== null) {
            $media->setCryptedFilter($response['crypted']);
        }

        return $this->createResponse($media);
    }

    /**
     * Add new media.
     *
     * @param Request $request
     *
     * @return Response media
     */
    public function postMediaAction(Request $request)
    {
        $response = $this->getFilter($request);
        if ($response['success'] === false) {
            return $response['value'];
        }
        $filter = $response['value'];

        $mm = $this->getMediaManager();

        $media = $mm->create();
        $media
            ->setFilter($filter)
            ->setCryptedFilter($response['crypted'])
            ;

        if (!$request->files->has('file')) {
            return new Response($this->get('translator')->trans('error.fileNotFound', array(), 'ApoutchikaMediaBundle'), 500);
        }

        $media->setFile($request->files->get('file'));

        if ($mm->save($media) === false) {
            $error = $mm->getError();

            return new Response($this->get('translator')->trans($error[0], $error[1], 'ApoutchikaMediaBundle'), 500);
        }

        return $this->createResponse($media, $request->request->has('iframe'));
    }

    /**
     * Edit media.
     *
     * Apply filter if is send (mirror, rotate, focus, crop)
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response media
     */
    public function putMediaAction(Request $request, $id)
    {
        $_PUT = json_decode($request->getContent(), true);

        $response = $this->getFilter($request);
        if ($response['success'] === false) {
            return $response['value'];
        }
        $filter = $response['value'];

        $mm = $this->getMediaManager();

        $media = $mm->findOneBy(array(
            'id' => $id,
            'filter' => $filter,
        ));

        if ($media === null) {
            return new Response($this->get('translator')->trans('error.mediaIdNotFound', array('%id%' => $id), 'ApoutchikaMediaBundle'));
        }

        $media->setCryptedFilter($response['crypted']);

        // Rotate
        if (isset($_PUT['rotate']) && in_array($_PUT['rotate'], array(-90, 90))) {
            $rotate = new Rotate($mm, $media);
            $rotate
                ->setAngle($_PUT['rotate'])
                ->apply();

            return $this->createResponse($media);
        }

// Mirror
        if (isset($_PUT['mirror']) && in_array($_PUT['mirror'], array('x', 'y'))) {
            $mirror = new Mirror($mm, $media);
            $mirror
                ->setDirection($_PUT['mirror'])
                ->apply();

            return $this->createResponse($media);
        }

// Crop
        if (
            isset($_PUT['x']) && is_numeric($_PUT['x']) &&
            isset($_PUT['y']) && is_numeric($_PUT['y']) &&
            isset($_PUT['w']) && is_numeric($_PUT['w']) &&
            isset($_PUT['h']) && is_numeric($_PUT['h'])
        ) {
            $crop = new Crop($mm, $media);
            $crop
                ->setSize(
                    $_PUT['x'],
                    $_PUT['y'],
                    $_PUT['w'],
                    $_PUT['h']
                )
                ->apply();

            return $this->createResponse($media);
        }

        if (isset($_PUT['name'])) {
            $media->setName($_PUT['name']);
        }
        if (isset($_PUT['description'])) {
            $media->setDescription($_PUT['description']);
        }
        if (isset($_PUT['alt'])) {
            $media->setAlt($_PUT['alt']);
        }
        if (isset($_PUT['focusLeft'])) {
            $media->setFocusLeft($_PUT['focusLeft']);
        }
        if (isset($_PUT['focusTop'])) {
            $media->setFocusTop($_PUT['focusTop']);
        }

        $mm->save($media);

        return $this->createResponse($media);
    }

    /**
     * Delete media.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response media
     */
    public function deleteMediaAction(Request $request, $id)
    {
        $response = $this->getFilter($request);
        if ($response['success'] === false) {
            return $response['value'];
        }
        $filter = $response['value'];

        $mm = $this->getMediaManager();

        $media = $mm->findOneBy(array(
            'id' => $id,
            'filter' => $filter,
        ));

        if ($media !== null) {
            $media->setCryptedFilter($response['crypted']);
            $mm->delete($media);
        }

        return $this->createResponse($media);
    }

    /**
     * Get is filter is send and verify if is a valid filter.
     *
     * @param Request $request
     *
     * @return Array
     */
    private function getFilter(Request $request)
    {
        $success = false;
        $filter = null;
        if ($request->query->has('filter')) {
            $filter = $request->query->get('filter');
            $type = 'query';
        } elseif ($request->request->has('filter')) {
            $filter = $request->request->get('filter');
            $type = 'request';
        } elseif ($request->getMethod() === 'PUT') {
            $_PUT = json_decode($request->getContent(), true);
            if (!empty($_PUT['filter'])) {
                $filter = $_PUT['filter'];
                $type = 'put';
            }
        }

        if ($filter === null) {
            if ($this->container->getParameter('kernel.environment') === 'dev') {
                $value = new Response($this->get('translator')->trans('error.requestMustHasFilter', array(), 'ApoutchikaMediaBundle'), 500);
            } else {
                $value = new Response($this->get('translator')->trans('error.requestMustHasFilterProd', array(), 'ApoutchikaMediaBundle'), 500);
            }
        } elseif (!$this->get('apoutchika_media.filter')->has($filter)) {
            if ($this->container->getParameter('kernel.environment') === 'dev') {
                $value = new Response($this->get('translator')->trans('error.requestMustHasCorrectFilter', array(), 'ApoutchikaMediaBundle'), 500);
            } else {
                $value = new Response($this->get('translator')->trans('error.requestMustHasCorrectFilterProd', array(), 'ApoutchikaMediaBundle'), 500);
            }
        } else {
            $success = true;
            $value = $this->get('apoutchika_media.filter')->get($filter);
        }

        return array(
            'success' => $success,
            'value' => $value,
            'crypted' => $filter,
        );
    }

    /**
     * Search medias.
     *
     * @param Request $request
     *
     * @return Response array of medias
     */
    public function searchMediaAction(Request $request)
    {
        $response = $this->getFilter($request);
        if ($response['success'] === false) {
            return $response['value'];
        }
        $filter = $response['crypted'];

        $mm = $this->getMediaManager();

        $q = ($request->query->has('q')) ? $request->query->get('q') : null;
        $type = ($request->query->has('type')) ? $request->query->get('type') : null;

        $medias = $mm->search($filter, $q, $type);

        return $this->createResponse($medias);
    }
}
