<?php

namespace Manhattan\Bundle\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Manhattan\Bundle\ContentBundle\Entity\Content;
use Manhattan\Bundle\ContentBundle\Form\ContentType;

/**
 * Public controller.
 *
 */
class PublicController extends Controller
{
    /**
     * Displays the Homepage
     *
     * @Route("", name="homepage")
     * @Template()
     */
    public function homepageAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $content = $em->getRepository('ManhattanContentBundle:Content')
            ->findOneBySlug('about-me');

        return array('content' => $content);
    }

}