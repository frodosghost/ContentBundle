<?php

namespace Manhattan\Bundle\ContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Manhattan\Bundle\ContentBundle\Entity\Content;
use Manhattan\Bundle\ContentBundle\Form\ContentType;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;
use Manhattan\Bundle\ContentBundle\Form\ChoiceList\ContentEntityLoader;

use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Content controller.
 */
class ContentController extends Controller
{
    /**
     * Lists all Content entities.
     */
    public function indexAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $controller = $this;
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('ManhattanContentBundle:Content');
        $htmlTree = $repository->childrenHierarchy(
            null,  /* starting from root nodes */
            false, /* load all children, not only direct */
            $options = array(
                'decorate' => true,
                'rootOpen' => '<ul class="nested-tree">',
                'rootClose' => '</ul>',
                'childOpen' => '<li>',
                'childClose' => '</li>',
                'nodeDecorator' => function($node) use (&$controller) {
                    switch ($node['publishState']) {
                        case 4:
                            $publish_btn = '<span class="btn-small btn-warning">Archived</span>';
                            break;
                        case 2:
                            $publish_btn = '<span class="btn-small btn-success">Published</span>';
                            break;
                        default:
                            $publish_btn = '<span class="btn-small btn-info">Draft</span>';
                            break;
                    }

                    $edit_link = '<a href="'.$controller->generateUrl("console_content_edit", array("id"=>$node['id'])).'">'.$node['title'].'</a>&nbsp;';

                    return $edit_link . $publish_btn;
                }
            )
        );

        return $this->render('ManhattanContentBundle:Content:index.html.twig', array(
            'tree' => $htmlTree
        ));

    }

    /**
     * Displays a form to create a new Content entity.
     */
    public function newAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $entity = new Content();
        $form   = $this->createForm(new ContentType(), $entity);

        return $this->render('ManhattanContentBundle:Content:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Content entity.
     */
    public function createAction(Request $request)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $entity  = new Content();

        $form    = $this->createForm(new ContentType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('console_content'));

        }

        return $this->render('ManhattanContentBundle:Content:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Content entity.
     */
    public function editAction(Request $request, $id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ManhattanContentBundle:Content')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }

        $choice_loader = new ContentEntityLoader($this, $em, $entity);
        $choice_list = new EntityChoiceList(
            $em,
            'Manhattan\Bundle\ContentBundle\Entity\Content',
            'title',
            $choice_loader
        );

        $editForm = $this->createForm(new ContentType($choice_list), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ManhattanContentBundle:Content:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        ));
    }

    /**
     * Edits an existing Content entity.
     */
    public function updateAction(Request $request, $id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ManhattanContentBundle:Content')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }

        $choice_loader = new ContentEntityLoader($this, $em, $entity);
        $choice_list = new EntityChoiceList(
            $em,
            'Manhattan\Bundle\ContentBundle\Entity\Content',
            'title',
            $choice_loader
        );

        $editForm   = $this->createForm(new ContentType($choice_list), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('console_content_edit', array('id' => $id)));
        }

        return $this->render('ManhattanContentBundle:Content:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        ));
    }

    /**
     * Deletes a Content entity.
     */
    public function deleteAction(Request $request, $id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $form = $this->createDeleteForm($id);

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ManhattanContentBundle:Content')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Content entity.');
            }

            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'The Content page was removed from the site');
        }

        return $this->redirect($this->generateUrl('console_content'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
