<?php

namespace Manhattan\Bundle\ContentBundle\Tests\Entity\Base;

/**
 * PublishTest
 *
 * @author James Rickard <james@frodosghost.com>
 */
class PublishTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test onCreate Behaviour
     */
    public function testGetPublishOptions()
    {
        $publish = $this->getMockForAbstractClass('Manhattan\Bundle\ContentBundle\Entity\Base\Publish');

        $publish_options = array(
            1 => 'Draft',
            2 => 'Publish',
            4 => 'Archive',
            8 => 'Locked'
        );

        $this->assertEquals($publish_options, $publish->getPublishOptions(), '->getPublishOptions() returns the correct array.');
    }

    /**
     * Tests that the retruned PublishDate is formatted correctly.
     */
    public function testGetDate()
    {
        $publish = $this->getMockForAbstractClass('Manhattan\Bundle\ContentBundle\Entity\Base\Publish');

        $publish->setPublishDate(new \DateTime());

        $date = new \DateTime();

        $this->assertEquals($date->format('Y-m-d'), $publish->getDate(), '->getDate() returns the correctly formatted Publish Date.');
    }

}
