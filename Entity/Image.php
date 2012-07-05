<?php

namespace AGB\Bundle\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

use AGB\Bundle\ContentBundle\Entity\Asset as Asset;
/**
 * AGB\Bundle\ContentBundle\Entity\Image
 *
 * @ORM\Table(name="content_image")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Image extends Asset
{
    /**
     * @ORM\ManyToOne(targetEntity="Content", inversedBy="images")
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id", onDelete="cascade")
     */
    private $content;

    public function __toString()
    {
        return $this->getWebPath();
    }

    /**
     * Add content
     *
     * @param AGB\Bundle\ContentBundle\Entity\Content $content
     */
    public function addContent(Content $content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getContent()
    {
        return $this->content;
    }

    public function getUploadDir()
    {
        return 'uploads/content/'. $this->getContent()->getSlug();
    }

}
