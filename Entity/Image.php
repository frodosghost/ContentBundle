<?php

namespace Manhattan\Bundle\ContentBundle\Entity;

use Manhattan\Bundle\ContentBundle\Entity\Asset as Asset;

/**
 * Manhattan\Bundle\ContentBundle\Entity\Image
 */
class Image extends Asset
{
    /**
     * @var Manhattan\Bundle\ContentBundle\Entity\Content
     */
    private $content;

    public function __toString()
    {
        return $this->getWebPath();
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
        return 'uploads/content/'. $this->getContent()->getSlug();
    }

}
