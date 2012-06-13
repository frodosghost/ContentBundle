<?php

namespace Manhattan\Bundle\ContentBundle\Tests\Form;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\Extension\Core\CoreExtension;

use Manhattan\Bundle\ContentBundle\Form\ContentType;

class ContentTypeTest extends \PHPUnit_Framework_TestCase
{
    private $factory;

    protected function setUp()
    {
        $ext = new CoreExtension();

        $this->factory = new FormFactory(array(
            $ext
        ));
    }

    protected function tearDown()
    {
        $this->factory = null;
    }

    public function testFormNameIsGallery()
    {
        $mock_gallery = $this->getMock('Manhattan\Bundle\ContentBundle\Entity\Content');
        $form = $this->factory->create(new ContentType(), $mock_gallery);

        $this->assertEquals('content', $form->getName());
    }

    public function testBuildFormHasCreateFields()
    {
        $mock_gallery = $this->getMock('Manhattan\Bundle\ContentBundle\Entity\Content');
        $form = $this->factory->create(new ContentType(), $mock_gallery);

        $this->assertTrue($form->has('title'),
            'The ContentType has a Title field.');

        $this->assertTrue($form->has('body'),
            'The ContentType has a Body field.');

    }

}
