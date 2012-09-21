<?php

namespace AGB\Bundle\ContentBundle\Tests\Entity;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class ContentRepositoryFunctionalTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        // Add data with Fixtures to include post listings
        $this->loadFixtures(array(
            'AGB\Bundle\ContentBundle\Tests\DataFixtures\ORM\FixtureLoader'
        ));
    }

    protected function tearDown()
    {
        $this->loadFixtures(array());
    }

    /**
     * Find no object when looking for published Item but is not specified
     */
    public function testNoPublishStateSet()
    {
        $content = $this->em
            ->getRepository('AGBContentBundle:Content')
            ->findOneBySlugInTree('foo');

        $this->assertEquals(NULL, $content,
            'findOneBySlugInTree() returns NULL if not Publish State is passed to Repository');
    }

    /**
     * Find Content object when published and first element in tree
     */
    public function testPublishStateSet()
    {
        $content = $this->em
            ->getRepository('AGBContentBundle:Content')
            ->setPublishState(2)
            ->findOneBySlugInTree('foo');

        $this->assertInstanceOf('AGB\Bundle\ContentBundle\Entity\Content', $content,
            'findOneBySlugInTree() returns Content object with query');
    }

    /**
     * Find draft element when no Publish State is set on repository
     */
    public function testDraftPublishState()
    {
        $content = $this->em
            ->getRepository('AGBContentBundle:Content')
            ->findOneBySlugInTree('foo-bar');

        $this->assertInstanceOf('AGB\Bundle\ContentBundle\Entity\Content', $content,
            'findOneBySlugInTree() returns Content object that is set as a Draft');
    }

    /**
     * Find Content object when published and first element in tree
     */
    public function testTwoPublishStateSet()
    {
        $content = $this->em
            ->getRepository('AGBContentBundle:Content')
            ->setPublishState(2)
            ->findOneByTwoSlugsInTree('foo', 'bar');

        $this->assertInstanceOf('AGB\Bundle\ContentBundle\Entity\Content', $content,
            'findOneByTwoSlugsInTree() returns Content object with query');
    }

    /**
     * Return null is the root node is DRAFT and the target node is PUBLISHED
     */
    public function testTwoUnpublishedRootNode()
    {
        $content = $this->em
            ->getRepository('AGBContentBundle:Content')
            ->setPublishState(2)
            ->findOneByTwoSlugsInTree('foo-bar', 'bar-foo');

        $this->assertEquals(NULL, $content,
            'findOneByTwoSlugsInTree() returns NULL root node is Draft and target node is Published');
    }

}
