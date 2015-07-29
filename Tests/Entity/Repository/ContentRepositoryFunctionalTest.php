<?php

namespace Manhattan\Bundle\ContentBundle\Tests\Entity;

use Doctrine\ORM\Tools\SchemaTool;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class ContentRepositoryFunctionalTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function setUp()
    {
        $this->em = $this->getContainer()->get('doctrine')->getManager();
        if (!isset($metadatas)) {
            $metadatas = $this->em->getMetadataFactory()->getAllMetadata();
        }
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropDatabase();
        if (!empty($metadatas)) {
            $schemaTool->createSchema($metadatas);
        }
        $this->postFixtureSetup();

        // Add data with Fixtures to include post listings
        $this->loadFixtures(array(
            'Manhattan\Bundle\ContentBundle\Tests\DataFixtures\ORM\FixtureLoader'
        ));
    }

    protected function tearDown()
    {
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropDatabase();
        $this->postFixtureSetup();

        $this->getContainer()->get('doctrine')->getConnection()->close();
        parent::tearDown();
    }

    /**
     * Find no object when looking for published Item but is not specified
     */
    public function testNoPublishStateSet()
    {
        $content = $this->em
            ->getRepository('ManhattanContentBundle:Content')
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
            ->getRepository('ManhattanContentBundle:Content')
            ->setPublishState(2)
            ->findOneBySlugInTree('foo');

        $this->assertInstanceOf('Manhattan\Bundle\ContentBundle\Entity\Content', $content,
            'findOneBySlugInTree() returns Content object with query');
    }

    /**
     * Find draft element when no Publish State is set on repository
     */
    public function testDraftPublishState()
    {
        $content = $this->em
            ->getRepository('ManhattanContentBundle:Content')
            ->findOneBySlugInTree('foo-bar');

        $this->assertInstanceOf('Manhattan\Bundle\ContentBundle\Entity\Content', $content,
            'findOneBySlugInTree() returns Content object that is set as a Draft');
    }

    /**
     * Find Content object when published and first element in tree
     */
    public function testTwoPublishStateSet()
    {
        $content = $this->em
            ->getRepository('ManhattanContentBundle:Content')
            ->setPublishState(2)
            ->findOneByTwoSlugsInTree('foo', 'bar');

        $this->assertInstanceOf('Manhattan\Bundle\ContentBundle\Entity\Content', $content,
            'findOneByTwoSlugsInTree() returns Content object with query');
    }

    /**
     * Return null is the root node is DRAFT and the target node is PUBLISHED
     */
    public function testTwoUnpublishedRootNode()
    {
        $content = $this->em
            ->getRepository('ManhattanContentBundle:Content')
            ->setPublishState(2)
            ->findOneByTwoSlugsInTree('foo-bar', 'bar-foo');

        $this->assertEquals(NULL, $content,
            'findOneByTwoSlugsInTree() returns NULL root node is Draft and target node is Published');
    }

    /**
     * Tests finding no documents when item has no documents attached
     */
    public function testFindByIdJoinDocumentsNone()
    {
        $content = $this->em
            ->getRepository('ManhattanContentBundle:Content')
            ->setPublishState(2)
            ->findOneByIdJoinDocuments(2);

        $this->assertInstanceOf('Manhattan\Bundle\ContentBundle\Entity\Content', $content,
            'findOneByIdJoinDocuments() returns Content object with query');

        $this->assertEquals(0, $content->getDocuments()->count(),
            '->getDocuments() returns a count of 0 with no documents attached.');
    }

    /**
     * Tests Finding Document as joined object with one database entry
     */
    public function testFindByIdJoinDocumentsOne()
    {
        $content = $this->em
            ->getRepository('ManhattanContentBundle:Content')
            ->findOneByIdJoinDocuments(5);

        $this->assertInstanceOf('Manhattan\Bundle\ContentBundle\Entity\Content', $content,
            'findOneByIdJoinDocuments() returns Content object with query');

        $this->assertEquals(1, $content->getDocuments()->count(),
            '->getDocuments() returns a count of 1 when one document is attached.');

        $this->assertInstanceOf('Manhattan\Bundle\ContentBundle\Entity\Document', $content->getDocuments()->first(),
            '->getDocuments() First element is returned matches the class Document');
    }

}
