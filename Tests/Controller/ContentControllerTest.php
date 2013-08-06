<?php

namespace Manhattan\Bundle\ContentBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class ContentControllerTest extends WebTestCase
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

        // Add data with Fixtures
        $this->loadFixtures(array(
            'Manhattan\Bundle\ConsoleBundle\Tests\DataFixtures\ORM\LoadAuthenticatedAdminUserData',
        ));
    }

    protected function tearDown()
    {
        $this->loadFixtures(array());

        $this->getContainer()->get('doctrine')->getConnection()->close();
        parent::tearDown();
    }

    public function testIndex()
    {
        $user = $this->em->getRepository('ManhattanConsoleBundle:User')->find(1);
        $this->loginAs($user, 'secured_area');
        $client = $this->makeClient(true);

        // Check index page functions
        $crawler = $client->request('GET', '/console/content');

        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
    }

    public function testCompleteScenario()
    {
        $user = $this->em->getRepository('ManhattanConsoleBundle:User')->find(1);
        $this->loginAs($user, 'secured_area');
        $client = $this->makeClient(true);

        // Create a new entry in the database
        $crawler = $client->request('GET', '/console/content');
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $crawler = $client->click($crawler->selectLink('Create a new page')->link());

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
        $form = $crawler->selectButton('Edit')->form(array(
            'content[title]'  => 'Foo',
        ));

        $client->submit($form);

        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('input[value="Foo"]')->count(), 'Title has been updated to [value="Foo"]');

        $form = $crawler->selectButton('Edit')->form(array(
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
