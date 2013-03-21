<?php

namespace Manhattan\Bundle\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Manhattan\Bundle\ContentBundle\Entity\Content;
use Manhattan\Bundle\ContentBundle\Entity\Document;
use Manhattan\Bundle\ContentBundle\Form\DocumentType;

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
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function documentsAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $content = $em->getRepository('ManhattanContentBundle:Content')
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
     * @Secure(roles="ROLE_ADMIN")
     * @Method("post")
     * @Template("ManhattanContentBundle:Document:documents.html.twig")
     */
    public function createAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $content = $em->getRepository('ManhattanContentBundle:Content')->findOneById($id);

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
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function editAction($id, $document_id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $document = $em->getRepository('ManhattanContentBundle:Document')
            ->findOneByIdJoinContent($document_id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Document entity.');
        }

        $editForm = $this->createForm(new DocumentType(), $document);

        return array(
            'entity'      => $document,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Edits an existing Content entity.
     *
     * @Route("/{id}/document/{document_id}/update", name="console_document_update")
     * @Secure(roles="ROLE_ADMIN")
     * @Method("post")
     * @Template("ManhattanContentBundle:Document:edit.html.twig")
     */
    public function updateAction($id, $document_id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $document = $em->getRepository('ManhattanContentBundle:Document')
            ->findOneByIdJoinContent($document_id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Document entity.');
        }

        $editForm = $this->createForm(new DocumentType(), $document);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($document);
            $em->flush();

            return $this->redirect($this->generateUrl('console_document_edit', array('id' => $id, 'document_id' => $document_id)));
        }

        return array(
            'entity'      => $document,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Deletes a Content entity.
     *
     * @Route("/{id}/document/{document_id}/delete", name="console_document_delete")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction($id, $document_id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('ManhattanContentBundle:Document')->find($document_id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Document entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('console_project_documents', array('id' => $id)));
    }

}
