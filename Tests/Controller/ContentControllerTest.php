<?php

namespace AGB\Bundle\ContentBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class ContentControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'test'
        ));

        // Check index page functions
        $crawler = $client->request('GET', '/console/content/');

        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
    }

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'test'
        ));

        // Create a new entry in the database
        $crawler = $client->request('GET', '/console/content/');
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'content[title]'  => 'Test',
            'content[body]'   => 'Text Body'
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertTrue($crawler->filter('td:contains("Test")')->count() > 0);

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Edit')->form(array(
            'content[title]'  => 'Foo',
            'content[body]'   => 'Text Update',
            'content[publish_state]' => 4
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();
        //print_r($client->getResponse()->getContent());
        //exit;
        // Check the element contains an attribute with value equals "Foo"
        $this->assertTrue($crawler->filter('[value="Foo"]')->count() > 0);
        $this->assertEquals(4, $crawler->filter('#content_publish_state option:contains("Archive")')->attr('value'),
            'Archive value has been set from the select box and updated to Archive value.');

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
    }

}
