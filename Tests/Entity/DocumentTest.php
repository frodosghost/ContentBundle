<?php

namespace AGB\Bundle\ContentBundle\Tests\Entity;

use AGB\Bundle\ContentBundle\Entity\Document;

/**
 * DocumentTest
 *
 * @author James Rickard <james@frodosghost.com>
 */
class DocumentTest extends \PHPUnit_Framework_TestCase
{
    public function testGetUploadDir()
    {
        $image = new Document();

        $mock_content = $this->getMock('AGB\Bundle\ContentBundle\Entity\Content');
        $mock_content->expects($this->any())
            ->method('getSlug')
            ->will($this->returnValue('foo-bar'));

        $image->addContent($mock_content);

        $this->assertEquals('uploads/documents/foo-bar', $image->getUploadDir(),
            '->getUploadDir() returns the correctly set directory.');
    }

}
