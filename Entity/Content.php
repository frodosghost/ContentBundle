<?php

namespace Manhattan\Bundle\ContentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Manhattan\PublishBundle\Entity\Publish;

/**
 * Manhattan\Bundle\ContentBundle\Entity\Content
 */
class Content extends Publish
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $title
     */
    private $title;

    /**
     * @var string $slug
     */
    private $slug;

    /**
     * @var integer $lft
     */
    private $lft;

    /**
     * @var integer $rgt
     */
    private $rgt;

    /**
     * @var integer $lvl
     */
    private $lvl;

    /**
     * @var integer $root
     */
    private $root;

    /**
     * * @var integer $parent
     */
    private $parent;

    /**
     * @var integer $children
     */
    private $children;

    /**
     * @var text $excerpt
     */
    private $excerpt;

    /**
     * @var text $body
     */
    private $body;

    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $images;

    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $documents;

    /**
     * @var integer $center_download
     */
    private $centerDownload;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->centerDownload = 0;

        parent::__construct();
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set excerpt
     *
     * @param text $excerpt
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;
    }

    /**
     * Get excerpt
     *
     * @return text
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * Set body
     *
     * @param text $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Get body
     *
     * @return text
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get left
     *
     * @return Content
     */
    public function getLeft()
    {
     return $this->lft;
    }

    /**
     * Get right
     *
     * @return Content
     */
    public function getRight()
    {
        return $this->rgt;
    }

    /**
     * Get root
     *
     * @return Content
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @return integer
     */
    public function getLevel()
    {
        return $this->lvl;
    }

    /**
     * Set parent
     *
     * @param Content $parent
     */
    public function setParent(Content $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent object
     *
     * @return Content
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get children
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add Image
     *
     * @param Manhattan\Bundle\ContentBundle\Entity\Image $image
     */
    public function addImage(Image $image)
    {
        $this->images[] = $image;
    }

    /**
     * Sets Image to persist with the object
     *
     * @param Doctrine\Common\Collections\ArrayCollection $images
     */
    public function setImages(Collection $images)
    {
        foreach ($images as $image) {
            $image->addContent($this);
        }

        $this->images = $images;
    }

    /**
     * Get Images
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add Document
     *
     * @param Manhattan\Bundle\ContentBundle\Entity\Document $document
     */
    public function addDocument(Document $document)
    {
        $document->addContent($this);
        $this->documents[] = $document;

        return $this;
    }

    /**
     * Get Documents
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Get centerDownload
     *
     * @return integer
     */
    public function getCenterDownload()
    {
        return $this->centerDownload;
    }

    /**
     * Set centerDownload
     *
     * @param integer $centerDownload
     */
    public function setCenterDownload($centerDownload)
    {
        $this->centerDownload = $centerDownload;

        return $this;
    }

}
