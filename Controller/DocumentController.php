<?php

namespace Manhattan\Bundle\ContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Manhattan\Bundle\ContentBundle\Entity\Content;
use Manhattan\Bundle\ContentBundle\Entity\Document;
use Manhattan\Bundle\ContentBundle\Form\DocumentType;

/**
 * Content controller.
 */
class DocumentController extends Controller
{
    /**
     * Finds and displays documents for a Content.
     */
    public function documentsAction(Request $request, $id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $content = $em->getRepository('ManhattanContentBundle:Content')
            ->findOneByIdJoinDocuments($id);

        if (!$content) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }

        $document = new Document();
        $document->addContent($content);
        $form  = $this->createForm(new DocumentType(), $document);

        return $this->render('ManhattanContentBundle:Document:documents.html.twig', array(
            'entity' => $content,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Photo entity.
     */
    public function createAction(Request $request, $id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $content = $em->getRepository('ManhattanContentBundle:Content')->findOneById($id);

        if (!$content) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }

        $document = new Document();
        $document->addContent($content);

        $form  = $this->createForm(new DocumentType(), $document);
        $form->bind($request);

        if ($form->isValid()) {
            $em->persist($document);
            $em->flush();

            return $this->redirect($this->generateUrl('console_content_documents', array('id' => $content->getId())));
        }

        return $this->render('ManhattanContentBundle:Document:documents.html.twig', array(
            'entity' => $content,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Document entity.
     */
    public function editAction(Request $request, $id, $document_id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $document = $em->getRepository('ManhattanContentBundle:Document')
            ->findOneByIdJoinContent($document_id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Document entity.');
        }

        $editForm = $this->createForm(new DocumentType(), $document);

        return $this->render('ManhattanContentBundle:Document:edit.html.twig', array(
            'entity'    => $document,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Edits an existing Content entity
     */
    public function updateAction(Request $request, $id, $document_id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $document = $em->getRepository('ManhattanContentBundle:Document')
            ->findOneByIdJoinContent($document_id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Document entity.');
        }

        $editForm = $this->createForm(new DocumentType(), $document);

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($document);
            $em->flush();

            return $this->redirect($this->generateUrl('console_content_document_edit', array('id' => $id, 'document_id' => $document_id)));
        }

        return $this->render('ManhattanContentBundle:Document:edit.html.twig', array(
            'entity'    => $document,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a Content entity.
     */
    public function deleteAction($id, $document_id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ManhattanContentBundle:Document')->find($document_id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Document entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('console_content_documents', array('id' => $id)));
    }

}
