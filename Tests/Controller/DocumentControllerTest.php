<?php

namespace Manhattan\Bundle\ContentBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DocumentControllerTest extends WebTestCase
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

    public function testDocuments()
    {
        $user = $this->em->getRepository('ManhattanConsoleBundle:User')->find(1);
        $this->loginAs($user, 'secured_area');
        $client = $this->makeClient(true);

        $crawler = $client->request('GET', '/console/content/new');
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'content[title]'  => 'Foo',
            'content[body]'   => 'Foo Bar',
            'content[publishState]' => 2
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertGreaterThan(0, $crawler->filter('ul.nested-tree a:contains("Foo")')->count(), 'New item appears in list and a:contains("Test")');

        // Follow to the Edit page to display link to Documents
        $crawler = $client->click($crawler->selectLink('Foo')->link());

        $crawler = $client->click($crawler->selectLink('Manage Documents')->link());
print_r($client->getResponse()->getContent());
exit;
        $this->assertEquals('Documents: Foo', $crawler->filter('h2')->text(), 'Manage Documents page shows correct heading.');

        $this->assertEquals(0, $crawler->filter('.document-list')->children()->count(), 'The Document List div is empty becuase nothing has been uploaded.');

        $form = $crawler->selectButton('Add Document')->form(array(
            'document[title]'       => 'Foo',
            'document[description]' => 'Foo Bar'
        ));
        // Test Upload file
        $document = new UploadedFile(
            __FILE__,
            'document.pdf',
            'application/pdf',
            123
        );
        $form['document[file]']->upload($document);

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertEquals(1, $crawler->filter('.document-list')->children()->count(),
            'The Document List has been updated with a single upload.');

        $crawler = $client->click($crawler->selectLink('Edit')->last()->link());

        $form = $crawler->selectButton('Edit')->form(array(
            'document[title]' => 'Bar'
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertTrue($crawler->filter('[value="Bar"]')->count() > 0,
            'Form has been updated.');

        /* Comment included so can return to checking form validation.
         *
           $form = $crawler->selectButton('Add Document')->form(array(
           'document[title]'       => '',
            'document[description]' => ''
        ));

        $client->submit($form);

        $this->assertEquals(1, $crawler->filter('input[id=document_title]')->siblings(),
            'The Document List has been updated with a single upload.');
        */

    }

}
