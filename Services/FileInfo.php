<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\Services;

use Apoutchika\MediaBundle\Model\Media;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileInfo
{
    /**
     * @var File
     */
    private $file;

    /**
     * Set File.
     *
     * @param File $file
     */
    public function __construct(File $file = null)
    {
        if ($file !== null) {
            $this->setFile($file);
        }
    }

    /**
     * Set File.
     *
     * @param File $file
     */
    public function setFile(File $file)
    {
        $this->file = $file;
    }

    /**
     * Get mime type of file.
     *
     * @return string
     */
    public function getMimeType()
    {
        if ($this->file instanceof UploadedFile) {
            return $this->file->getClientMimeType();
        }

        return $this->file->getMimeType();
    }

    /**
     * Get original name of file.
     *
     * @return string
     */
    public function getName()
    {
        if ($this->file instanceof UploadedFile) {
            return $this->file->getClientOriginalName();
        }

        return $this->file->getFilename();
    }

    /**
     * Get type of file. It's a constace in Media class.
     *
     * @return int
     */
    public function getType()
    {
        $mimeType = preg_replace('#^([^/]+)/.*$#', '$1', $this->getMimeType());

        if ($mimeType == 'video') {
            return Media::VIDEO;
        }

        if ($mimeType == 'audio') {
            return Media::AUDIO;
        }

        if ($mimeType == 'image') {
            return Media::IMAGE;
        }

        $extension = $this->getExtension();

        if (in_array($extension, $this->imagesExtensions())) {
            return Media::IMAGE;
        }

        if (in_array($extension, $this->audiosExtensions())) {
            return Media::AUDIO;
        }

        if (in_array($extension, $this->videosExtensions())) {
            return Media::VIDEO;
        }

        return Media::OTHER;
    }

    /**
     * Get extension of original file.
     *
     * @return string
     */
    public function getExtension()
    {
        return preg_replace(
            '#^.*\.([^.]+)$#',
            '$1',
            strtolower($this->getName($this->file))
        );
    }

    /**
     * Get images extensions.
     *
     * @return array
     */
    private function imagesExtensions()
    {
        return array(
            'jpg', 'jpeg', 'jpe', 'gif',
            'png', 'bmp', 'tif', 'tiff',
            'ico',
        );
    }

    /**
     * Get audios extensions.
     *
     * @return array
     */
    private function audiosExtensions()
    {
        return array(
            'aac', 'ac3', 'aif', 'aiff',
            'm3a', 'm4a', 'm4b', 'mka',
            'mp1', 'mp2', 'mp3', 'ogg',
            'oga', 'ram', 'wav', 'wma',
        );
    }

    /**
     * Get videos extensions.
     *
     * @return array
     */
    private function videosExtensions()
    {
        return array(
            '3g2', '3gp', '3gpp', 'asf',
            'avi', 'divx', 'dv', 'flv',
            'm4v', 'mkv', 'mov', 'mp4',
            'mpeg', 'mpg', 'mpv', 'ogm',
            'ogv', 'qt', 'rm', 'vob',
            'wmv',
        );
    }
}
