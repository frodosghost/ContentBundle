<?php

namespace Manhattan\Bundle\ContentBundle\Tests\Entity;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class DocumentRepositoryFunctionalTest extends WebTestCase
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
            'Manhattan\Bundle\ContentBundle\Tests\DataFixtures\ORM\FixtureLoader'
        ));
    }

    protected function tearDown()
    {
        $this->loadFixtures(array());
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