<?php

namespace Manhattan\Bundle\ContentBundle\Tests\Entity;

use Doctrine\ORM\Tools\SchemaTool;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class DocumentRepositoryFunctionalTest extends WebTestCase
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

    public function testOneByIdJoinContent()
    {
        $document = $this->em
            ->getRepository('ManhattanContentBundle:Document')
            ->findOneByIdJoinContent(1);

        $this->assertInstanceOf('Manhattan\Bundle\ContentBundle\Entity\Document', $document,
            'testOneByIdJoinContent() returns Document object with query');

        $this->assertInstanceOf('Manhattan\Bundle\ContentBundle\Entity\Content', $document->getContent(),
            '->getContent() returns a Content object.');

        $this->assertEquals(5, $document->getContent()->getId(),
            '->getContent() returns the correct Content object.');
    }
}
