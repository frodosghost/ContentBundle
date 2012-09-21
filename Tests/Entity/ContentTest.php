<?php

namespace AGB\Bundle\ContentBundle\Tests\Entity;

use AGB\Bundle\ContentBundle\Entity\Content;

/**
 * ContentTest
 *
 * @author James Rickard <james@frodosghost.com>
 */
class ContentTest extends \PHPUnit_Framework_TestCase
{
    public function testPrePersist()
    {
        $content = new Content();

        // Run function
        $content->prePersist();

        $this->assertTrue($content->getCreatedAt() instanceof \DateTime,
            '->prePersist() sets the CreatedAt field correctly.');

        $this->assertTrue($content->getUpdatedAt() instanceof \DateTime,
            '->prePersist() sets the UpdatedAt field correctly.');
    }

    public function testPreUpdate()
    {
        $content = new Content();

        // Run function
        $content->preUpdate();

        $this->assertTrue($content->getUpdatedAt() instanceof \DateTime,
            '->preUpdate() sets the UpdatedAt field correctly.');
    }

    public function testPublishState()
    {
        $content = new Content();

        $this->assertEquals($content->getPublishState(), 1,
            '->getPublishState() returns 1 as value when no Publish State has not been set.');

        $content->setPublishState(16);

        $this->assertEquals($content->getPublishState(), 16,
            '->getPublishState() returns 16 as value when Publish State setter has been used.');
    }

}
