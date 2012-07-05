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
class ContentController extends Controller
{
    /**
     * Lists all Content entities.
     *
     * @Route("/", name="console_content")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('AGBContentBundle:Content')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Content entity.
     *
     * @Route("/{id}/show", name="console_content_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AGBContentBundle:Content')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Content entity.
     *
     * @Route("/new", name="console_content_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Content();
        $form   = $this->createForm(new ContentType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Content entity.
     *
     * @Route("/create", name="console_content_create")
     * @Method("post")
     * @Template("AGBContentBundle:Content:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Content();
        $request = $this->getRequest();
        $form    = $this->createForm(new ContentType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('content_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Content entity.
     *
     * @Route("/{id}/edit", name="console_content_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AGBContentBundle:Content')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }

        $choice_loader = new ContentEntityLoader($this, $em, $entity);
        $choice_list = new EntityChoiceList(
            $em,
            'AGB\Bundle\ContentBundle\Entity\Content',
            'title',
            $choice_loader
        );

        $editForm = $this->createForm(new ContentType($choice_list), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Content entity.
     *
     * @Route("/{id}/update", name="console_content_update")
     * @Method("post")
     * @Template("AGBContentBundle:Content:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AGBContentBundle:Content')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }

        $choice_loader = new ContentEntityLoader($this, $em, $entity);
        $choice_list = new EntityChoiceList(
            $em,
            'AGB\Bundle\ContentBundle\Entity\Content',
            'title',
            $choice_loader
        );

        $editForm   = $this->createForm(new ContentType($choice_list), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('content_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Content entity.
     *
     * @Route("/{id}/delete", name="console_content_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('AGBContentBundle:Content')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Content entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('content'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
