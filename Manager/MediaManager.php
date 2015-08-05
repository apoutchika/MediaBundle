<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\Manager;

use Apoutchika\MediaBundle\Model\MediaInterface;
use Apoutchika\MediaBundle\Factory\AliasManipulatorFactory;
use Apoutchika\MediaBundle\Services\AliasManipulator;
use Apoutchika\MediaBundle\Services\Image\Resize;
use Apoutchika\MediaBundle\Services\Image\Focus;
use Apoutchika\MediaBundle\Services\FileInfo;
use Apoutchika\MediaBundle\Services\Filter;
use Apoutchika\MediaBundle\Filesystem\FilesystemManipulator;
use Imagine\Image\ImagineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MediaManager extends BaseManager
{
    /**
     * List of contexts with keys => extensions.
     *
     * @var array
     */
    protected $contexts;

    /**
     * File system manipulator (gaufrette).
     *
     * @var Apoutchika\MediaBundle\Filesystem\FilesystemInterface
     */
    protected $filesystemManipulator;

    /**
     * The directory with original media.
     *
     * @var string
     */
    protected $originalDir;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var AliasManipulatorFactory
     */
    protected $aliasManipulatorFactory;

    /**
     * Error when send media.
     *
     * @var string
     */
    protected $error = null;

    /**
     * Trusted extensions, if media is not trusted, add .txt in extension.
     */
    protected $trustedExtensions;

    /**
     * @var ImagineInterface
     */
    protected $imagine;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var int
     */
    private $limit;

    /**
     * @param string $originalDir       Name of directory for original media
     * @param array  $contexts          List of contexts
     * @param array  $trustedExtensions
     * @param int    $limit
     */
    public function __construct($originalDir, array $contexts, array $trustedExtensions, $limit)
    {
        $this->originalDir = $originalDir;
        $this->contexts = $contexts;
        $this->trustedExtensions = $trustedExtensions;
        $this->limit = $limit;
    }

    /**
     * @param ImagineInterface $imagine
     */
    public function setImagine(ImagineInterface $imagine)
    {
        $this->imagine = $imagine;
    }

    /**
     * set filter.
     *
     * @param Filter $filter
     */
    public function setFilter(Filter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * File system manipulator.
     *
     * @param Apoutchika\MediaBundle\Filesystem\FilesystemManipulator
     */
    public function setFilesystemManipulator(FilesystemManipulator $filesystemManipulator)
    {
        $this->filesystemManipulator = $filesystemManipulator;
    }

    /**
     * @param AliasManipulatorFactory $aliasManipulatorFactory
     */
    public function setAliasManipulatorFactory(AliasManipulatorFactory $aliasManipulatorFactory)
    {
        $this->aliasManipulatorFactory = $aliasManipulatorFactory;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Create reference for media.
     *
     * @param MediaInterface $media
     *
     * @return bool
     */
    public function createReference(MediaInterface $media)
    {
        $allowed = false;
        foreach ($this->contexts as $context) {
            if (in_array($media->getExtension(), $context)) {
                $allowed = true;
                break;
            }
        }

        if ($allowed === false) {
            $this->error = array('error.extensionNotAllowed', array('%extension%' => $media->getExtension()));

            return false;
        }

        // secure extension for reference
        if (!in_array($media->getExtension(), $this->trustedExtensions)) {
            $media->setExtension($media->getExtension().'.txt');
            $media->setType($media::OTHER);
        }

        do {
            $media->setReference(sha1(microtime(true).mt_rand()).'.'.$media->getExtension());
        } while ($this->filesystemManipulator->has($this->originalDir.'/'.$media->getReference()));

        return true;
    }

    /**
     * Query for search medias with keywords and type.
     *
     * @param string $filterKey Allowed filter for this search
     * @param string $q         keywords
     * @param int    $type      Type of media, constant of MediaInterface
     *
     * @return array list id of medias matches with query
     */
    public function search($filterKey, $q = null, $type = null)
    {
        if ($type == 0) {
            $type = null;
        }

        if (!$this->filter->has($filterKey)) {
            return array();
        }

        $qb = $this->entityManager->getRepository($this->class)->createQueryBuilder('m')
            ->select('m.id as id')
            ;

        $filter = $this->filter->get($filterKey);
        if ($filter === null) {
            $qb->where('m.filter IS NULL');
        } else {
            $qb->where('m.filter = :filter')
                ->setParameter('filter', $filter)
                ;
        }

        if ($q !== null) {
            foreach (explode(' ', $q) as $i => $word) {
                $qb->andWhere(
                    $qb->expr()->orX(
                        $qb->expr()->like('m.name', ':q'.$i),
                        $qb->expr()->like('m.description', ':q'.$i),
                        $qb->expr()->like('m.alt', ':q'.$i)
                    )
                )
                ->setParameter('q'.$i, '%'.$word.'%')
                ;
            }
        }

        if ($type != null) {
            $qb->andWhere('m.type = :type')
                ->setParameter('type', $type)
                ;
        }

        $results = $qb->getQuery()
            ->getArrayResult();

        $ids = array();
        foreach ($results as $media) {
            $ids[] = $media['id'];
        }

        return $ids;
    }

    /**
     * Custom find all, sort by createAt desc.
     *
     * @param array $filtersKeys List medias in this filters
     */
    public function findAllByFiltersKeys(array $filtersKeys)
    {
        $qb = $this->entityManager->getRepository($this->class)->createQueryBuilder('m');

        $filters = array();
        $addNull = false;
        $inversedfilters = array();
        foreach ($filtersKeys as $filter) {
            if ($this->filter->has($filter)) {
                $value = $this->filter->get($filter);
                $inversedfilters[$value] = $filter;

                if ($value === null) {
                    $addNull = true;
                } else {
                    $filters[] = $value;
                }
            }
        }

        if ($addNull === false && empty($filters)) {
            return array();
        }

        if (!empty($filters)) {
            $qb
                ->select('m')
                ->where($qb->expr()->in('m.filter', $filters))
                ;
        }

        if ($addNull === true) {
            $qb->orWhere('m.filter IS NULL');
        }

        $medias = $qb->orderBy('m.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;

        foreach ($medias as $media) {
            $media->setCryptedFilter($inversedfilters[$media->getFilter()]);
        }

        return $medias;
    }

    /**
     * Delete media entity and files.
     *
     * @param MediaInterface $media
     * @param bool           $flush
     */
    public function delete($media, $flush = true)
    {
        parent::delete($media, $flush);

        foreach ($this->filesystemManipulator->keys() as $key) {
            if (preg_match('#/'.$media->getReference().'$#', $key)) {
                $this->filesystemManipulator->delete($key);
            }
        }
    }

    /**
     * Save new file.
     *
     * @param MediaInterface $media
     *
     * @return MediaInterface
     */
    private function saveFile(MediaInterface $media)
    {
        $file = $media->getFile();

        $fileInfo = new FileInfo($file);
        $type = $fileInfo->getType();

        $media->setType($type);

        if ($media->getName() === null) {
            $media->setName($fileInfo->getName());
            $media->setAlt($fileInfo->getName());
        }

        $media
            ->setMimeType($fileInfo->getMimeType())
            ->setExtension($fileInfo->getExtension())
            ;

        if (!$this->createReference($media)) {
            return false;
        }

        $key = $this->originalDir.'/'.$media->getReference();
        $this->filesystemManipulator->save($key, $file->getPathname());

        $this->setSizeWidthAndHeight($media);

        $media->setFile();

        if ($this->limit === null || $media->getType() !== $media::IMAGE) {
            return $media;
        }

        // resize if image > limit
        if ($media->getWidth() > $this->limit || $media->getHeight() > $this->limit) {
            $resize = new Resize($this, $media);
            $resize->setTargetSize($this->limit, $this->limit);
            $resize->apply();
        }

        return $media;
    }

    /**
     * Set size of file, if it's image, set width and height.
     *
     * @param MediaInterface $media
     *
     * @return MediaInterface
     */
    public function setSizeWidthAndHeight(MediaInterface $media)
    {
        $key = $this->originalDir.'/'.$media->getReference();

        $media->setSize($this->filesystemManipulator->size($key));

        if ($media->getType() === $media::IMAGE) {
            $image = $this->imagine->load(
                $this->filesystemManipulator->read($key)
            );
            $size = $image->getSize();
            $media->setWidth($size->getWidth());
            $media->setHeight($size->getHeight());
        }

        return $this;
    }

    /**
     * Get relative url of media.
     *
     * @param MediaInterface    $media
     * @param string|array|null $alias
     *
     * return string relative Url of media ex: /medias/123x456_focus/9485990a0e13c3ef2adddb69a75a64d44c40caa7.jpg
     */
    public function getUrl(MediaInterface $media, $alias = null)
    {
        return $this->filesystemManipulator->url($this->getAliasAndKey($media, $alias));
    }

    /**
     * Get absolute url of media.
     *
     * @param MediaInterface    $media
     * @param string|array|null $alias
     *
     * return string Url of media ex: http://www.exemple.tld/medias/123x456_focus/9485990a0e13c3ef2adddb69a75a64d44c40caa7.jpg
     */
    public function getAbsoluteUrl(MediaInterface $media, $alias = null)
    {
        return $this->filesystemManipulator->url($this->getAliasAndKey($media, $alias), true);
    }

    /**
     * Get path of media.
     *
     * @param MediaInterface    $media
     * @param string|array|null $alias
     *
     * return string path of media ex: /var/www/exemple.tld/web/medias/123x456_focus/9485990a0e13c3ef2adddb69a75a64d44c40caa7.jpg
     */
    public function getPath(MediaInterface $media, $alias = null)
    {
        return $this->filesystemManipulator->path($this->getAliasAndKey($media, $alias));
    }

    /**
     * Get content of file.
     *
     * @param MediaInterface    $media
     * @param string|array|null $alias
     *
     * @return string
     */
    public function getContent(MediaInterface $media, $alias = null)
    {
        return $this->filesystemManipulator->read($this->getAliasAndKey($media, $alias));
    }

    /**
     * Get key of media for filesystem. If image with alias, crop it.
     *
     * @param MediaInterface    $media
     * @param string|array|null $alias
     *
     * @return string
     */
    public function getAliasAndKey(MediaInterface $media, $alias = null)
    {
        $aliasManipulator = $this->aliasManipulatorFactory->setAlias($alias);

        if ($media->getType() === $media::IMAGE) {
            $alias = $aliasManipulator->getAliasName();

            if ($alias !== $this->originalDir) {
                $this->cropImage($media, $aliasManipulator);
            }
        } else {
            $alias = $this->originalDir;
        }

        return $alias.'/'.$media->getReference();
    }

    /**
     * Crop image if is not exists in alias.
     *
     * @param MediaInterface   $media
     * @param AliasManipulator $aliasManipulator
     */
    private function cropImage(MediaInterface $media, AliasManipulator $aliasManipulator)
    {
        $aliasName = $aliasManipulator->getAliasName();
        $aliasArray = $aliasManipulator->getAliasArray();
        $mediaPath = $aliasName.'/'.$media->getReference();

        if ($this->filesystemManipulator->has($mediaPath)) {
            return true;
        }

        $mediaOriginalPath = $this->originalDir.'/'.$media->getReference();

        if (!$this->filesystemManipulator->has($mediaOriginalPath)) {
            throw new \Exception('The file \''.$mediaOriginalPath.'\' is not found.');
        }

        if ($aliasArray['focus'] === true) {
            $magicFocus = new Focus($this, $media);
            $magicFocus
                ->setTargetSize($aliasArray['width'], $aliasArray['height'])
                ->setFocus($media->getFocusLeft(), $media->getFocusTop())
                ->apply($aliasName)
                ;

            return true;
        }

        $resize = new Resize($this, $media);
        $resize
            ->setTargetSize($aliasArray['width'], $aliasArray['height'])
            ->apply($aliasName)
            ;

        return true;
    }

    /**
     * Render html of media.
     *
     * @param MediaInterface    $media
     * @param string|array|null $alias
     *
     * @return string render of media html
     */
    public function getHtml(MediaInterface $media, $alias = null)
    {
        if ($media->getType() == $media::IMAGE) {
            $template = 'ApoutchikaMediaBundle:Render:image.html.twig';
        } elseif ($media->getType() == $media::VIDEO) {
            $template = 'ApoutchikaMediaBundle:Render:video.html.twig';
        } elseif ($media->getType() == $media::AUDIO) {
            $template = 'ApoutchikaMediaBundle:Render:audio.html.twig';
        } else {
            $template = 'ApoutchikaMediaBundle:Render:other.html.twig';
        }

        return $this->container->get('templating')->render($template, array(
            'media' => $media,
            'alias' => $alias,
            'url' => $this->getAbsoluteUrl($media, $alias),
        ));
    }

    /**
     * Remove empty directory 
     * Remove cache of media.
     *
     * @param MediaInterface|null $media     If is defined, only cache of this file is removed
     * @param bool                $onlyFocus Remove media only for focus cache
     */
    public function reset(MediaInterface $media = null, $onlyFocus = false)
    {
        if ($onlyFocus === true) {
            $regex = '#^\d*x\d*_focus/';
        } else {
            $regex = '#^\d*x\d*(_focus)?/';
        }

        $regex .= ($media !== null) ? $media->getReference() : '.*';
        $regex .= '$#';

        foreach ($this->filesystemManipulator->keys() as $file) {
            if (preg_match($regex, $file)) {
                $this->filesystemManipulator->delete($file);
            }
        }
    }

    /**
     * Save media.
     *
     * @param MediaInterface $media
     * @param bool           $flush
     *
     * @return bool If return false (when add new file), the error is in $error attribute
     */
    public function save($media, $flush = true)
    {
        if ($media->getFile() !== null) {
            if ($this->saveFile($media) === false) {
                return false;
            }
        }

        if ($media->getFocusIsEdited() === true) {
            $this->reset($media, true);
            $media->setFocusIsEdited(false);
        }

        $media->setUpdatedAt(new \Datetime());

        parent::save($media, $flush);

        return true;
    }

    /**
     * get Errors.
     *
     * @return string Error when add new file
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Get imagine.
     *
     * @return ImagineInterface
     */
    public function getImagine()
    {
        return $this->imagine;
    }

    /**
     * Get filesystem manipulator.
     *
     * @return FilesystemInterface
     */
    public function getFilesystemManipulator()
    {
        return $this->filesystemManipulator;
    }

    /**
     * Get name of original directory.
     *
     * @return string
     */
    public function getOriginalDir()
    {
        return $this->originalDir;
    }

    /**
     * Create media object.
     *
     * @param string $path path of media
     *
     * @return MediaInterface
     */
    public function create($path = null)
    {
        $media = new $this->class();

        if ($path !== null) {
            $media->setFile(new \Symfony\Component\HttpFoundation\File\File($path));
        }

        return $media;
    }
}
