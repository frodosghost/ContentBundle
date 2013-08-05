<?php

namespace Manhattan\Bundle\ContentBundle\Tests\Entity;

use Manhattan\Bundle\ContentBundle\Entity\Content;

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
        $content->onCreate();

        $this->assertTrue($content->getCreatedAt() instanceof \DateTime, '->onCreate() sets the CreatedAt field correctly.');

        $this->assertTrue($content->getUpdatedAt() instanceof \DateTime, '->prePersist() sets the UpdatedAt field correctly.');
    }

    public function testPreUpdate()
    {
        $content = new Content();

        // Run function
        $content->onUpdate();

        $this->assertTrue($content->getUpdatedAt() instanceof \DateTime, '->onUpdate() sets the UpdatedAt field correctly.');
    }

}
