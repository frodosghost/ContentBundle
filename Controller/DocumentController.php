<?php

namespace AGB\Bundle\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use AGB\Bundle\ContentBundle\Entity\Content;
use AGB\Bundle\ContentBundle\Form\ContentType;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;
use AGB\Bundle\ContentBundle\Form\ChoiceList\ContentEntityLoader;

use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Content controller.
 *
 * @Route("/console/content")
 */
class DocumentController extends Controller
{
    /**
     * Finds and displays documents for a Content.
     *
     * @Route("/{id}/documents", name="console_project_documents")
     * @Template()
     */
    public function documentsAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $content = $em->getRepository('AGBContentBundle:Content')
            ->findOneByIdJoinDocuments($id);
    }

}
