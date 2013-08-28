<?php

namespace Manhattan\Bundle\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Manhattan\Bundle\ContentBundle\Entity\Content;
use Manhattan\Bundle\ContentBundle\Form\ContentType;

/**
 * Public controller.
 *
 */
class PublicController extends Controller
{
    /**
     * Displays page on the root of the NestedTree pages
     */
    public function oneSlugAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $content = $em->getRepository('ManhattanContentBundle:Content')
            ->setPublishState($this->container->getParameter('manhattan.constant.publish'))
            ->findOneBySlugInTree($slug);

        if (!($content instanceof Content)) {
            throw $this->createNotFoundException(sprintf('Exception: 404 Page Not Found. Unable to find single-slug page with URI: "%s"', $this->getRequest()->getUri()));
        }

        return $this->render('ManhattanContentBundle:Public:content.html.twig', array(
            'content' => $content,
            'parent'  => null,
            'pages'   => $content->getChildren($content, true)
        ));
    }

    /**
     * Displays page on the root of the NestedTree pages
     */
    public function twoSlugAction($slug_one, $slug_two)
    {
        $em = $this->getDoctrine()->getManager();

        $content = $em->getRepository('ManhattanContentBundle:Content')
            ->setPublishState($this->container->getParameter('manhattan.constant.publish'))
            ->findOneByTwoSlugsInTree($slug_one, $slug_two);

        if (!($content instanceof Content)) {
            throw $this->createNotFoundException(sprintf('Exception: 404 Page Not Found. Unable to find double-slug page with URI: "%s"', $this->getRequest()->getUri()));
        }

        $parent = $content->getParent();

        return $this->render('ManhattanContentBundle:Public:content.html.twig', array(
            'content' => $content,
            'parent'  => $parent,
            'pages'   => $parent->getChildren($parent, true)
        ));
    }

    /**
     * Sends 404 to Page AtomLogger
     *
     * @param  string     $message  Error Message to be Loggers
     * @param  \Exception $previous Previous message made prior to 404
     * @return NotFoundHttpException
     */
    public function createNotFoundException($message = 'Not Found', \Exception $previous = null)
    {
        if ($this->has('atom.404.logger')) {
            $log = $this->get('atom.404.logger');
            $log->addRecord(400, $message, array('request' => $this->getRequest()->getUri()));
        }

        return new NotFoundHttpException($message, $previous);
    }

}
