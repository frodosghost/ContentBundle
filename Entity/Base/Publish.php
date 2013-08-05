<?php

namespace Manhattan\Bundle\ContentBundle\Entity\Base;

/**
 * Manhattan\Bundle\ContentBundle\Entity\Base
 *
 * This abstract class is for easy addition of the publish state field to be used with models.
 *
 */
abstract class Publish
{
    /**
     * Publish States
     *
     * @link(Bitwise Operators, http://php.net/manual/en/language.operators.bitwise.php)
     */
    const DRAFT = 1;

    const PUBLISH = 2;

    const ARCHIVE = 4;

    const LOCKED = 8;

    /**
     * @var integer
     */
    protected $publishState;

    /**
     * @var \DateTime
     */
    protected $publishDate;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->publishState = 1;
    }

    /**
     * Set setPublishDate
     *
     * @param  DateTime $publishDate
     * @return Publish
     */
    public function setPublishDate(\DateTime $publishDate)
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    /**
     * Get publishDate
     *
     * @return \DateTime
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }

    /**
     * Returns format for URL
     */
    public function getDate()
    {
        return $this->getPublishDate()->format('Y-m-d');
    }

    /**
     * Set publishState
     *
     * @param integer $publishState
     * @return Publish
     */
    public function setPublishState($publishState)
    {
        $this->publishState = $publishState;

        if ($publishState === self::PUBLISH) {
            $this->setPublishDate(new \DateTime());
        }

        return $this;
    }

    /**
     * Get publishState
     *
     * @return integer
     */
    public function getPublishState()
    {
        return $this->publishState;
    }

    /**
     * Returns array of static values for configuring form select values
     *
     * @return srray
     */
    public function getPublishOptions()
    {
        return array(
            self::DRAFT   => 'Draft',
            self::PUBLISH => 'Publish',
            self::ARCHIVE => 'Archive',
            self::LOCKED  => 'Locked'
        );
    }

}
