<?php

namespace AGB\Bundle\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use AGB\Bundle\ContentBundle\Entity\Content;
use AGB\Bundle\ContentBundle\Entity\Document;
use AGB\Bundle\ContentBundle\Form\DocumentType;

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

        if (!$content) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }

        $document = new Document();
        $document->addContent($content);
        $form  = $this->createForm(new DocumentType(), $document);

        return array(
            'entity' => $content,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Photo entity.
     *
     * @Route("/{id}/document/create", name="console_document_create")
     * @Method("post")
     * @Template("AGBContentBundle:Document:documents.html.twig")
     */
    public function createAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $content = $em->getRepository('AGBContentBundle:Content')->findOneById($id);

        if (!$content) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }

        $document = new Document();
        $document->addContent($content);

        $request = $this->getRequest();
        $form  = $this->createForm(new DocumentType(), $document);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em->persist($document);
            $em->flush();

            return $this->redirect($this->generateUrl('console_project_documents', array('id' => $content->getId())));
        }

        return array(
            'entity' => $content,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Document entity.
     *
     * @Route("/{id}/document/{document_id}/edit", name="console_document_edit")
     * @Template()
     */
    public function editAction($id)
    {

    }

    /**
     * Edits an existing Content entity.
     *
     * @Route("/{id}/document/{document_id}/update", name="console_document_update")
     * @Method("post")
     * @Template("AGBContentBundle:Document:edit.html.twig")
     */
    public function updateAction($id)
    {

    }

    /**
     * Deletes a Content entity.
     *
     * @Route("/{id}/document/{document_id}/delete", name="console_document_delete")
     */
    public function deleteAction($id)
    {

    }

}
