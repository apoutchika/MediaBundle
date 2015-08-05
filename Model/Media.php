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

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Abstract Media.
 *
 * @ORM\MappedSuperclass
 * @ExclusionPolicy("all")
 */
abstract class Media implements MediaInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Expose
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255, nullable=true)
     * @Expose
     */
    protected $alt;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Expose
     */
    protected $description;

    /**
     * @var int
     *
     * @ORM\Column(name="width", type="integer", nullable=true)
     * @Expose
     */
    protected $width;

    /**
     * @var int
     *
     * @ORM\Column(name="height", type="integer", nullable=true)
     * @Expose
     */
    protected $height;

    /**
     * @var int
     *
     * @ORM\Column(name="size", type="integer")
     * @Expose
     */
    protected $size;

    /**
     * @var string
     *
     * @ORM\Column(name="mime_type", type="string", length=65)
     * @Expose
     */
    protected $mimeType;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=25)
     * @Expose
     */
    protected $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255)
     * @Expose
     */
    protected $reference;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Expose
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     * @Expose
     */
    protected $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="integer")
     * @Expose
     */
    protected $type;

    /**
     * @var decimal
     *
     * @ORM\Column(name="focus_left", type="float", options={"default" = 50})
     * @Expose
     */
    protected $focusLeft;

    /**
     * @var decimal
     *
     * @ORM\Column(name="focus_top", type="float", options={"default" = 50})
     * @Expose
     */
    protected $focusTop;

    /**
     * @var string
     *
     * @ORM\Column(name="filter", type="string", length=255, nullable=true)
     */
    protected $filter;

    /**
     * @var string
     * @Expose
     * @SerializedName("filter")
     */
    protected $cryptedFilter;

    /**
     * @var bool
     *
     * Only for MediaManager: if the focus is edited, clear cache of media contexts
     */
    protected $focusIsEdited;

    /**
     * @var \Symfony\Component\HttpFoundation\File\File
     *
     * Only for MediaManager: On persist, if the file is defined, move id on media original directory
     */
    protected $file;

    /**
     * @var Array
     *
     * Media urls
     * @Expose
     */
    protected $urls;

    /**
     * @var string
     *
     * Get media html for reference
     * @Expose
     */
    protected $html;

    const OTHER = 1;
    const IMAGE = 2;
    const VIDEO = 3;
    const AUDIO = 4;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \Datetime();
        $this->updatedAt = new \Datetime();

        $this->focusLeft = 50;
        $this->focusTop = 50;
        $this->focusIsEdited = false;

        $this->filter = null;
    }

    /**
     * Entity to string.
     *
     * @return string
     */
    public function __toString()
    {
        return (!empty($this->name)) ? $this->name : 'n/a';
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Media
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set alt.
     *
     * @param string $alt
     *
     * @return Media
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt.
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Media
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set width.
     *
     * @param int $width
     *
     * @return Media
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width.
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height.
     *
     * @param int $height
     *
     * @return Media
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height.
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set size.
     *
     * @param int $size
     *
     * @return Media
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size.
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set mimeType.
     *
     * @param string $mimeType
     *
     * @return Media
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType.
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set extension.
     *
     * @param string $extension
     *
     * @return Media
     */
    public function setExtension($extension)
    {
        $this->extension = strtolower($extension);

        return $this;
    }

    /**
     * Get extension.
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set reference.
     *
     * @param string $reference
     *
     * @return Media
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference.
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Media
     */
    public function setCreatedAt(\Datetime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return Media
     */
    public function setUpdatedAt(\Datetime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set type.
     *
     * @param int $type
     *
     * @return Media
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set focusLeft.
     *
     * @param float $focusLeft
     *
     * @return Media
     */
    public function setFocusLeft($focusLeft)
    {
        if ($focusLeft !== $this->focusLeft) {
            $this->focusIsEdited = true;
            $this->focusLeft = $focusLeft;
        }

        return $this;
    }

    /**
     * Get focusLeft.
     *
     * @return float
     */
    public function getFocusLeft()
    {
        return $this->focusLeft;
    }

    /**
     * Set focusTop.
     *
     * @param float $focusTop
     *
     * @return Media
     */
    public function setFocusTop($focusTop)
    {
        if ($focusTop !== $this->focusTop) {
            $this->focusIsEdited = true;
            $this->focusTop = $focusTop;
        }

        return $this;
    }

    /**
     * Get focusTop.
     *
     * @return float
     */
    public function getFocusTop()
    {
        return $this->focusTop;
    }

    /**
     * Set filter.
     *
     * @param string $filter
     *
     * @return Media
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * Get filter.
     *
     * @return string
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Set cryptedFilter.
     *
     * @param string $cryptedFilter
     *
     * @return Media
     */
    public function setCryptedFilter($cryptedFilter)
    {
        $this->cryptedFilter = $cryptedFilter;

        return $this;
    }

    /**
     * Get cryptedFilter.
     *
     * @return string
     */
    public function getCryptedFilter()
    {
        return $this->cryptedFilter;
    }

    /**
     * Set focusIsEdited.
     *
     * @param bool $focusIsEdited
     *
     * @return Media
     */
    public function setFocusIsEdited($focusIsEdited)
    {
        $this->focusIsEdited = $focusIsEdited;

        return $this;
    }

    /**
     * Get focusIsEdited.
     *
     * @return bool
     */
    public function getFocusIsEdited()
    {
        return $this->focusIsEdited;
    }

    /**
     * Set file.
     *
     * @param \Symfony\Component\HttpFoundation\File\File $file
     *
     * @return Media
     */
    public function setFile(File $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file.
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set urls.
     *
     * @param array $urls
     *
     * @return Media
     */
    public function setUrls(array $urls)
    {
        $this->urls = $urls;

        return $this;
    }

    /**
     * Get urls.
     *
     * @return array
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * Set html.
     *
     * @param string $html
     *
     * @return Media
     */
    public function setHtml($html)
    {
        $this->html = $html;

        return $this;
    }

    /**
     * Get html.
     *
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }
}
