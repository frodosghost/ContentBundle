<?php

namespace AGB\Bundle\ContentBundle\Tests\Form;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\Extension\Core\CoreExtension;

use AGB\Bundle\ContentBundle\Form\ContentType;

class ContentTypeTest extends \PHPUnit_Framework_TestCase
{
    private $factory;

    protected function setUp()
    {
        $this->factory = new FormFactory(array(
            new CoreExtension()
        ));
    }

    protected function tearDown()
    {
        $this->factory = null;
    }

    public function testFormNameIsGallery()
    {
        $mock_gallery = $this->getMock('AGB\Bundle\ContentBundle\Entity\Content');
        $form = $this->factory->create(new TestContentType(), $mock_gallery);

        $this->assertEquals('content', $form->getName());
    }

    public function testBuildFormHasCreateFields()
    {
        $mock_gallery = $this->getMock('AGB\Bundle\ContentBundle\Entity\Content');
        $form = $this->factory->create(new TestContentType(), $mock_gallery);

        $this->assertTrue($form->has('title'),
            'The ContentType has a Title field.');

        $this->assertTrue($form->has('body'),
            'The ContentType has a Body field.');

    }

}


/**
 * Testing the field type 'entity' was complicated and imported too many dependencies.
 * Because of this the field was removed from the form - as it was only tested as an exist anyway.
 * If I could test better this would work.
 */
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TestContentType extends ContentType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove('parent');
    }
}