<?php

namespace Manhattan\Bundle\ContentBundle\Tests\Entity;

use Manhattan\Bundle\ContentBundle\Entity\Image;

/**
 * ImageTest
 *
 * @author James Rickard <james@frodosghost.com>
 */
class ImageTest extends \PHPUnit_Framework_TestCase
{
    public function testGetUploadDir()
    {
        $image = new Image();

        $this->assertEquals('uploads/content', $image->getUploadDir(),
            '->getUploadDir() returns the correctly set directory.');
    }

}
