<?php

namespace Manhattan\Bundle\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

use Manhattan\Bundle\ContentBundle\Entity\Asset;
use Manhattan\Bundle\ContentBundle\Entity\Content;

/**
 * Manhattan\Bundle\ContentBundle\Entity\Document
 *
 * @ORM\Table(name="content_document")
 * @ORM\Entity(repositoryClass="Manhattan\Bundle\ContentBundle\Entity\DocumentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Document extends Asset
{
    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Please enter a Title."
     * )
     */
    private $title;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Content", inversedBy="documents")
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id", onDelete="cascade")
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