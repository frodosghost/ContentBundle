<?php

namespace Manhattan\Bundle\ContentBundle\Tests\Controller;

use Doctrine\ORM\Tools\SchemaTool;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class ContentControllerTest extends WebTestCase
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
            'Manhattan\Bundle\ConsoleBundle\Tests\DataFixtures\ORM\LoadAuthenticatedAdminUserData'
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

    public function testIndex()
    {
        $user = $this->em->getRepository('ManhattanConsoleBundle:User')->find(1);
        $this->loginAs($user, 'secured_area');
        $client = $this->makeClient(true);

        // Check index page functions
        $domain = static::$kernel->getContainer()->getParameter('domain');
        $crawler = $client->request('GET', '/content', array(), array(), array(
            'HTTP_HOST'       => 'console.'. $domain,
            'HTTP_USER_AGENT' => 'Symfony2 BrowserKit'
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCompleteScenario()
    {
        $user = $this->em->getRepository('ManhattanConsoleBundle:User')->find(1);
        $this->loginAs($user, 'secured_area');
        $client = $this->makeClient(true);

        // Create a new entry in the database
        $domain = static::$kernel->getContainer()->getParameter('domain');
        $crawler = $client->request('GET', '/content', array(), array(), array(
            'HTTP_HOST'       => 'console.'. $domain,
            'HTTP_USER_AGENT' => 'Symfony2 BrowserKit'
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $crawler = $client->click($crawler->selectLink('Create a New Page')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'content[title]'  => 'Test',
            'content[body]'   => 'Text Body'
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('ul.nested-tree a:contains("Test")')->count(), 'New item appears in list and a:contains("Test")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Test')->link());
        $form = $crawler->selectButton('Update and Save')->form(array(
            'content[title]'  => 'Foo',
        ));

        $client->submit($form);

        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('input[value="Foo"]')->count(), 'Title has been updated to [value="Foo"]');

        $form = $crawler->selectButton('Update and Save')->form(array(
            'content[title]'  => 'Foo',
            'content[body]'   => 'Text Update',
            'content[publishState]' => 4,
            'content[centerDownload]' => 1
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('input[value="Foo"]')->count());

        $this->assertEquals(4, $crawler->filter('#content_publishState option:contains("Archive")')->attr('value'),
            'Archive value has been set from the select box and updated to Archive value.');

        $this->assertEquals(1, $crawler->filter('#content_centerDownload option:contains("Main Content")')->attr('value'),
            'Center Download value has been set from the select box and updated to Main Content value.');

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();
    }

}
