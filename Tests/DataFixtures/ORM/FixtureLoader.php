<?php

namespace Manhattan\Bundle\ContentBundle\Tests\DataFixtures\ORM;
 
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Manhattan\Bundle\ContentBundle\Entity\Content;
use Manhattan\Bundle\ContentBundle\Entity\Document;
 
class FixtureLoader implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $content = new Content();
        $content->setTitle('Foo');
        $content->setBody('<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>');
        $content->setPublishState(2);

        $manager->persist($content);

        $content_two = new Content();
        $content_two->setTitle('Bar');
        $content_two->setBody('<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>');
        $content_two->setParent($content);
        $content_two->setPublishState(2);

        $manager->persist($content_two);

        $content_three = new Content();
        $content_three->setTitle('Foo Bar');
        $content_three->setBody('<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>');
        $manager->persist($content_three);

        $content_four = new Content();
        $content_four->setTitle('Bar Foo');
        $content_four->setBody('<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>');
        $content_four->setParent($content_three);
        $content_four->setPublishState(2);
        $manager->persist($content_four);

        $download = new Document();
        $download->setTitle('foo');
        $download->setFilename('document.pdf');
        $download->setMimeType('application/pdf');

        $content_download = new Content();
        $content_download->setTitle('Content Download');
        $content_download->setBody('<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>');
        $content_download->addDocument($download);

        $manager->persist($content_download);

        $manager->flush();
    }

}
