<?php

namespace Manhattan\Bundle\ContentBundle\Tests\Entity;

use Manhattan\Bundle\ContentBundle\Entity\Document;

/**
 * DocumentTest
 *
 * @author James Rickard <james@frodosghost.com>
 */
class DocumentTest extends \PHPUnit_Framework_TestCase
{
    public function testGetUploadDir()
    {
        $document = new Document();

        $mock_content = $this->getMock('Manhattan\Bundle\ContentBundle\Entity\Content');
        $mock_content->expects($this->any())
            ->method('getSlug')
            ->will($this->returnValue('foo-bar'));

        $document->addContent($mock_content);

        $this->assertEquals('uploads/documents/foo-bar', $document->getUploadDir(),
            '->getUploadDir() returns the correctly set directory.');
    }

    public function testPreUpload()
    {
        $document = new Document();

        // Setup mock class for testing upload file
        $mock_file = $this->getMockBuilder('\Symfony\Component\HttpFoundation\File\UploadedFile')
            ->disableOriginalConstructor()
            ->getMock();

        $mock_file->expects($this->any())
            ->method('getMimetype')
            ->will($this->returnValue('foo'));
        $mock_file->expects($this->any())
            ->method('getClientOriginalName')
            ->will($this->returnValue('Foo Bar 239*.jpg'));
        $mock_file->expects($this->any())
            ->method('guessExtension')
            ->will($this->returnValue('jpg'));

        $document->setFile($mock_file);
        $document->preUpload();

        $this->assertEquals('foo-bar-239-.jpg', $document->getFilename(),
            '->getFilename() corrects the filename as set when uploaded');
    }

    /**
     * Tests the extension as returned from the Filename
     */
    public function testGetExtension()
    {
        $document = new Document();
        $document->setFilename('foo-bar-239-.jpg');

        $this->assertEquals('jpg', $document->getExtension(),
            '->getExtension() returns the correct file extension');

        $document->setFilename('foo-bar-239-.file.png');

        $this->assertEquals('png', $document->getExtension(),
            '->getExtension() returns the correct file extension');

        $document->setFilename('foo-bar-239-.');

        $this->assertEquals('', $document->getExtension(),
            '->getExtension() returns the correct file extension');

        $document->setFilename('');

        $this->assertEquals('', $document->getExtension(),
            '->getExtension() returns the correct file extension');

        $document->setFilename(NULL);

        $this->assertEquals('', $document->getExtension(),
            '->getExtension() returns the correct file extension');
    }

}
