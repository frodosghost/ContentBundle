<?php

namespace AGB\Bundle\ContentBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class DocumentControllerTest extends WebTestCase
{

    public function testDocuments()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'test'
        ));

        $crawler = $client->request('GET', '/console/content/new');
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'content[title]'  => 'Foo',
            'content[body]'   => 'Foo Bar',
            'content[publish_state]' => 2
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertTrue($crawler->filter('td:contains("Foo")')->count() > 0);

        // Follow to the Edit page to display link to Documents
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $crawler = $client->click($crawler->selectLink('Manage Documents')->link());

    }

}
