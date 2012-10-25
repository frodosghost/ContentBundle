<?php

namespace Manhattan\Bundle\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Manhattan\Bundle\ContentBundle\Entity\Content
 *
 * @ORM\Table(name="content")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="Manhattan\Bundle\ContentBundle\Entity\ContentRepository")
 */
class Content
{
    /**
     * Publish States
     *
     * @link(Bitwise Operators, http://php.net/manual/en/language.operators.bitwise.php)
     */
    const DRAFT = 1;

    const PUBLISH = 2;

    const ARCHIVE = 4;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(type="integer", nullable=true)
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Content", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Content", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @var text $body
     *
     * @ORM\Column(name="body", type="text")
     * @Assert\NotBlank(
     *     message = "Please enter some text for the body of the page."
     * )
     */
    private $body;

    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="content", cascade={"persist", "remove"})
     */
    private $images;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Manhattan\Bundle\ContentBundle\Entity\Document", mappedBy="content", cascade={"persist", "remove"}
     * )
     */
    private $documents;

    /**
     * var integer $center_download
     * 
     * @ORM\Column(name="center_download", type="integer")
     */
    private $center_download;

    /**
     * var integer $publish_state
     * 
     * @ORM\Column(name="publish_state", type="integer")
     */
    private $publish_state;

    /**
     * @var datetime $created_at
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Assert\Type("\DateTime")
     */
    private $created_at;

    /**
     * @var datetime $updated_at
     *
     * @ORM\Column(name="updated_at", type="datetime")
     * @Assert\Type("\DateTime")
     */
    private $updated_at;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->publish_state = 1;
        $this->center_download = 0;
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
    public function setImages(\Doctrine\Common\Collections\Collection $images)
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
     * Set publish_state
     *
     * @param integer $publish_state
     */
    public function setPublishState($publish_state)
    {
        $this->publish_state = $publish_state;

        return $this;
    }

    /**
     * Get center_download
     *
     * @return integer 
     */
    public function getCenterDownload()
    {
        return $this->center_download;
    }

    /**
     * Set center_download
     *
     * @param integer $center_download
     */
    public function setCenterDownload($center_download)
    {
        $this->center_download = $center_download;

        return $this;
    }

    /**
     * Get publish_state
     *
     * @return integer 
     */
    public function getPublishState()
    {
        return $this->publish_state;
    }

    /**
     * Set created_at
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->created_at = $createdAt;
    }

    /**
     * Get created_at
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updated_at = $updatedAt;
    }

    /**
     * Get updated_at
     *
     * @return datetime 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function preUpdate() {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * Returns array of static values for configuring form select values
     * 
     * @return srray
     */
    public function getStaticArray()
    {
        return array(
            self::DRAFT => 'Draft',
            self::PUBLISH => 'Publish',
            self::ARCHIVE => 'Archive'
        );
    }

}
