<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\Model;

use Symfony\Component\HttpFoundation\File\File;

interface MediaInterface
{
    /**
     * Constructor.
     */
    public function __construct();

    /**
     * Entity to string.
     *
     * @return string
     */
    public function __toString();

    /**
     * Get id.
     *
     * @return int
     */
    public function getId();

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Media
     */
    public function setName($name);

    /**
     * Get name.
     *
     * @return string
     */
    public function getName();

    /**
     * Set alt.
     *
     * @param string $alt
     *
     * @return Media
     */
    public function setAlt($alt);

    /**
     * Get alt.
     *
     * @return string
     */
    public function getAlt();

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Media
     */
    public function setDescription($description);

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Set width.
     *
     * @param int $width
     *
     * @return Media
     */
    public function setWidth($width);

    /**
     * Get width.
     *
     * @return int
     */
    public function getWidth();

    /**
     * Set height.
     *
     * @param int $height
     *
     * @return Media
     */
    public function setHeight($height);

    /**
     * Get height.
     *
     * @return int
     */
    public function getHeight();

    /**
     * Set size.
     *
     * @param int $size
     *
     * @return Media
     */
    public function setSize($size);

    /**
     * Get size.
     *
     * @return int
     */
    public function getSize();

    /**
     * Set mimeType.
     *
     * @param string $mimeType
     *
     * @return Media
     */
    public function setMimeType($mimeType);

    /**
     * Get mimeType.
     *
     * @return string
     */
    public function getMimeType();

    /**
     * Set extension.
     *
     * @param string $extension
     *
     * @return Media
     */
    public function setExtension($extension);

    /**
     * Get extension.
     *
     * @return string
     */
    public function getExtension();

    /**
     * Set reference.
     *
     * @param string $reference
     *
     * @return Media
     */
    public function setReference($reference);

    /**
     * Get reference.
     *
     * @return string
     */
    public function getReference();

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Media
     */
    public function setCreatedAt(\Datetime $createdAt);

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return Media
     */
    public function setUpdatedAt(\Datetime $updatedAt);

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt();

    /**
     * Set type.
     *
     * @param int $type
     *
     * @return Media
     */
    public function setType($type);

    /**
     * Get type.
     *
     * @return int
     */
    public function getType();

    /**
     * Set focusLeft.
     *
     * @param float $focusLeft
     *
     * @return Media
     */
    public function setFocusLeft($focusLeft);

    /**
     * Get focusLeft.
     *
     * @return float
     */
    public function getFocusLeft();

    /**
     * Set focusTop.
     *
     * @param float $focusTop
     *
     * @return Media
     */
    public function setFocusTop($focusTop);

    /**
     * Get focusTop.
     *
     * @return float
     */
    public function getFocusTop();

    /**
     * Set filter.
     *
     * @param string $filter
     *
     * @return Media
     */
    public function setFilter($filter);

    /**
     * Get filter.
     *
     * @return string
     */
    public function getFilter();

    /**
     * Set cryptedFilter.
     *
     * @param string $cryptedFilter
     *
     * @return Media
     */
    public function setCryptedFilter($cryptedFilter);

    /**
     * Get cryptedFilter.
     *
     * @return string
     */
    public function getCryptedFilter();

    /**
     * Set focusIsEdited.
     *
     * @param bool $focusIsEdited
     *
     * @return Media
     */
    public function setFocusIsEdited($focusIsEdited);

    /**
     * Get focusIsEdited.
     *
     * @return bool
     */
    public function getFocusIsEdited();

    /**
     * Set file.
     *
     * @param \Symfony\Component\HttpFoundation\File\File $file
     *
     * @return Media
     */
    public function setFile(File $file = null);

    /**
     * Get file.
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function getFile();

    /**
     * Set urls.
     *
     * @param array $urls
     *
     * @return Media
     */
    public function setUrls(array $urls);

    /**
     * Get urls.
     *
     * @return array
     */
    public function getUrls();

    /**
     * Set html.
     *
     * @param string $html
     *
     * @return Media
     */
    public function setHtml($html);

    /**
     * Get html.
     *
     * @return string
     */
    public function getHtml();
}
