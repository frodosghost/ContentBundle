<?php

namespace Manhattan\Bundle\ContentBundle\Tests\Controller;

use Doctrine\ORM\Tools\SchemaTool;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class PublicControllerTest extends WebTestCase
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

        // Add data with Fixtures
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

    public function testOneSlugAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/foo');

        $this->assertTrue(200 === $client->getResponse()->getStatusCode(),
            'Correct status code is returned when the page has been set correctly');

        $this->assertEquals(1, $crawler->filter('h1:contains("Foo")')->count(),
            'The "H1" tag contains correct header');
    }

    public function testNotFoundOneSlug()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/bar-foo');

        $this->assertTrue($client->getResponse()->isNotFound(),
            'Page Not Found if the Publish State has not been set.');
    }

    public function testTwoSlugAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/foo/bar');

        $this->assertTrue(200 === $client->getResponse()->getStatusCode(),
            'Correct status code is returned when the page has been set correctly');

        $this->assertEquals(1, $crawler->filter('h1:contains("Bar")')->count(),
            'The "H1" tag contains correct header');
    }

    public function testNotFoundTwoSlug()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/bar/foo');

        $this->assertTrue($client->getResponse()->isNotFound(),
            'Page Not Found if the Publish State has not been set.');
    }

}
