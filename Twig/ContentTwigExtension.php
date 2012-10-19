<?php

/*
 * This file is part of the AGB Web Bundle.
 */

namespace AGB\Bundle\ContentBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Twig Extension for displaying latest news
 *
 * @author James Rickard <james@frodosghost.com>
 */
class ContentTwigExtension extends \Twig_Extension
{
    private $environment;

    private $template;

    private $twig_template;

    private $doctrine;

    /**
     * @param \Twig_Environment $environment
     * @param RegistryInterface $doctrine
     * @param string            $template
     */
    public function __construct(\Twig_Environment $environment, RegistryInterface $doctrine, $template)
    {
        $this->environment = $environment;
        $this->doctrine = $doctrine;
        $this->template = $template;
    }

    public function getFunctions()
    {
        return array(
            'document_downloads' => new \Twig_Function_Method($this, 'renderDownloads', array('is_safe' => array('html')))
        );
    }

    /**
     * Builds and returns Twig Template
     */
    public function getTemplate()
    {
        if (!$this->twig_template instanceof \Twig_Template) {
            $this->twig_template = $this->environment->loadTemplate($this->template);
        }

        return $this->twig_template;
    }

    /**
     * Renders Latest News Items displaying excerpt below heading
     *
     * @param  integer $item_count
     * @param  array   $options
     * @return string
     */
    public function renderDownloads($entity, $wrapper_class, array $options = array())
    {
        $html = $this->getTemplate()->renderBlock('render_downloads', array(
            'documents'     => $entity->getDocuments(),
            'wrapper_class' => $wrapper_class,
            'options'       => $options
        ));

        return $html;
    }

    /**
     * @return RegistryInterface
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'agb_content_twig';
    }
}
