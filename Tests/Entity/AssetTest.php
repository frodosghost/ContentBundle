<?php

namespace AGB\Bundle\ContentBundle\Tests\Entity;

use AGB\Bundle\ContentBundle\Entity\Asset;

/**
 * AssetTest
 *
 * @author James Rickard <james@frodosghost.com>
 */
class AssetTest extends \PHPUnit_Framework_TestCase
{
    private $_asset = null;

    public function setUp()
    {
        $stub = $this->getMockForAbstractClass('AGB\Bundle\ContentBundle\Entity\Asset');
        $this->_asset = $stub;
    }

    public function tearDown()
    {
        $this->_asset = null;
    }

    public function testHasAsset()
    {
        // Test returns false when no data exists in the object
        $this->assertFalse($this->_asset->hasAsset(),
            ' ->hasAsset() returns false when no data has been set.');

        // Insert a filename to help the function check the data
        $this->_asset->setFilename('foo');

        $this->assertTrue($this->_asset->hasAsset(),
            '->hasAsset() returns true when the filename has been set.');
    }

    public function testGetAbsolutePath()
    {
        $this->_asset->expects($this->any())
            ->method('getUploadDir')
            ->will($this->returnValue('bar'));

        $this->_asset->setFilename('foo');

        // Setup a realpath because of the __DIR__ used in the absolute path function
        $test_path = realpath(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..').DIRECTORY_SEPARATOR.'Entity';

        $this->assertEquals($test_path. '/../../../../../../../web/bar/foo', $this->_asset->getAbsolutePath(),
            '->getAbsolutePath() returns the correct path when data is set.');
    }

    public function testGetWebPath()
    {
        $this->_asset->expects($this->any())
            ->method('getUploadDir')
            ->will($this->returnValue('foo'));

        $this->_asset->setFilename('bar');

        $this->assertEquals('foo/bar', $this->_asset->getWebPath(),
            '->getWebPath() returns the correct path when data is set.');
    }

    public function testPreUpload()
    {
        // Setup mock class for testing upload file
        $mock_file = $this->getMockBuilder('\Symfony\Component\HttpFoundation\File\UploadedFile')
            ->disableOriginalConstructor()
            ->getMock();

        $mock_file->expects($this->any())
            ->method('getMimetype')
            ->will($this->returnValue('foo'));
        $mock_file->expects($this->any())
            ->method('getClientOriginalName')
            ->will($this->returnValue('bar'));

        // Sets the mock class and initiates the preupload function
        $this->_asset->setFile($mock_file);
        $this->_asset->preUpload();

        $this->assertEquals('foo', $this->_asset->getMimeType(),
            '->preUpload() correctly adds the mime_type when preparing file object.');

        $this->assertEquals('bar', $this->_asset->getFilename(),
            '->preUpload() correctly adds the filename when preparing file object.');
    }

    public function testUpload()
    {
        // Setup mock class for testing upload file
        $mock_file = $this->getMockBuilder('\Symfony\Component\HttpFoundation\File\UploadedFile')
            ->disableOriginalConstructor()
            ->getMock();

        // Sets the mock class and initiates the preupload function
        $this->_asset->setFile($mock_file);
        $this->_asset->upload();

        $this->assertEquals(NULL, $this->_asset->getFile(),
            '->upload() removes the file variable from the object.');
    }


    public function testUploadException()
    {
        // Setup mock class for testing upload file
        $mock_file = $this->getMockBuilder('\Symfony\Component\HttpFoundation\File\UploadedFile')
            ->disableOriginalConstructor()
            ->getMock();

        // Sets exception to be sent and caught
        $mock_file->expects($this->any())
             ->method('move')
             ->will($this->throwException(new \Symfony\Component\HttpFoundation\File\Exception\UploadException));

        // Sets the mock class and initiates the preupload function
        $this->_asset->setFile($mock_file);

        $this->setExpectedException('Symfony\Component\HttpFoundation\File\Exception\UploadException');
        $this->_asset->upload();
    }

}
