<?php

namespace Manhattan\Bundle\ContentBundle\Entity;

use Manhattan\Bundle\ContentBundle\Entity\Asset;
use Manhattan\Bundle\ContentBundle\Entity\Content;

/**
 * Manhattan\Bundle\ContentBundle\Entity\Document
 */
class Document extends Asset
{
    /**
     * @var string $title
     */
    private $title;

    /**
     * @var text $description
     */
    private $description;

    /**
     * @var Manhattan\Bundle\ContentBundle\Entity\Content
     */
    private $content;


    public function __toString()
    {
        return $this->getWebPath();
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Photo
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
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
     * Set description
     *
     * @param text $description
     * @return Photo
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add content
     *
     * @param Manhattan\Bundle\ContentBundle\Entity\Content $content
     */
    public function addContent(Content $content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return Manhattan\Bundle\ContentBundle\Entity\Content
     */
    public function getContent()
    {
        return $this->content;
    }

    public function getUploadDir()
    {
        return 'uploads/documents/'. $this->getContent()->getSlug();
    }

    /**
     * @ORM\PrePersist()
     */
    public function preUpload()
    {
        if (null === $this->getFile()) {
            return;
        }

        $this->setMimeType($this->getFile()->getMimetype());

        // set the path property to the filename where you'ved saved the file
        $filename = $this->sanitise($this->getFile()->getClientOriginalName());
        $this->setFilename($filename);
    }

}
