<?php

namespace AGB\Bundle\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\HttpFoundation\File\Exception\UploadException;

/**
 * AGB\Bundle\ContentBundle\Entity\Asset
 *
 * @ORM\Table(name="asset")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="class_name", type="string")
 * @ORM\DiscriminatorMap({
 * "content_image" = "AGB\Bundle\ContentBundle\Entity\Image",
 * "news_image" = "AGB\Bundle\NewsBundle\Entity\Image",
 * "slideshow_image" = "AGB\Bundle\SlideshowBundle\Entity\Image",
 * })
 */
abstract class Asset
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $mime_type
     *
     * @ORM\Column(name="mime_type", type="string", length=255)
     */
    private $mime_type;

    /**
     * @var string $filename
     *
     * @ORM\Column(name="filename", type="string", length=255)
     */
    private $filename;

    /**
     * @var string $file
     *
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * Variable for holding delete variable
     */
    private $remove_filename;

    /**
     * @var datetime $created_at
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    /**
     * @var datetime $updated_at
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updated_at;

    public function __toString()
    {
        return $this->getFilename();
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
     * Set mime_type
     *
     * @param string $mimeType
     */
    public function setMimeType($mimeType)
    {
        $this->mime_type = $mimeType;
    }

    /**
     * Get mime_type
     *
     * @return string 
     */
    public function getMimeType()
    {
        return $this->mime_type;
    }

    /**
     * Set filename
     *
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set file
     *
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }

    public function hasAsset()
    {
        return ($this->filename != null || $this->filename != '');
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


    public function getAbsolutePath()
    {
        return $this->hasAsset() ? $this->getUploadRootDir().'/'.$this->getFilename() : null;
    }

    public function getWebPath()
    {
        return $this->hasAsset() ? $this->getUploadDir().'/'.$this->getFilename() : null;
    }

    private function getUploadRootDir()
    {
        return __DIR__.'/../../../../../../../web/'.$this->getUploadDir();
    }

    abstract public function getUploadDir();

    /**
     * @ORM\PrePersist()
     */
    public function preUpload()
    {
        if (null === $this->file) {
            return;
        }

        $this->setMimeType($this->file->getMimetype());

        // set the path property to the filename where you'ved saved the file
        $this->setFilename($this->file->getClientOriginalName());
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        try
        {
            $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());
        }
        catch (Exception $e)
        {
            throw new UploadException($e->getMessage());
        }

        // clean up the file property as you won't need it anymore
        $this->setFile(null);
    }

    /**
     * Assest is updated and requires image replacement this will replace the current image while retaining
     * the field identifier.
     *
     * @ORM\PostUpdate()
     */
    public function replace()
    {
        if (null === $this->file) {
            return false;
        }

        // Remove existing image from file system
        $this->storeFilenameForRemove();
        $this->removeUpload();

        return true;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist() {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }
    
    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate() {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->remove_filename = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($this->remove_filename && is_file($this->remove_filename)) {
            unlink($this->remove_filename);
        }
    }

}