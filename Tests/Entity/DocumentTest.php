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

        $this->assertEquals('uploads/documents', $document->getUploadDir(),
            '->getUploadDir() returns the correctly set directory.');
    }

    public function testPreUpload()
    {
        $document = new Document();

        // Setup mock class for testing upload file
        $mock_file = $this->createUploadedFileMock('abcdef', 'Foo Bar 239*.doc', true);

        $mock_file->expects($this->any())
            ->method('getMimetype')
            ->will($this->returnValue('foo'));
        $mock_file->expects($this->any())
            ->method('guessExtension')
            ->will($this->returnValue('doc'));

        $document->setFile($mock_file);
        $document->preUpload();

        $this->assertEquals('foo-bar-239-.doc', $document->getFilename(),
            '->getFilename() corrects the filename as set when uploaded');
    }

    private function createUploadedFileMock($name, $originalName, $valid)
    {
        $file = $this
            ->getMockBuilder('Symfony\Component\HttpFoundation\File\UploadedFile')
            ->setConstructorArgs(array(__DIR__.'/../DataFixtures/foo', 'foo'))
            ->getMock()
        ;
        $file
            ->expects($this->any())
            ->method('getBasename')
            ->will($this->returnValue($name))
        ;
        $file
            ->expects($this->any())
            ->method('getClientOriginalName')
            ->will($this->returnValue($originalName))
        ;
        $file
            ->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue($valid))
        ;

        return $file;
    }

}
