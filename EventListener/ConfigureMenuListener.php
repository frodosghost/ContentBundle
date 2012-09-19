<?php

namespace AGB\Bundle\ContentBundle\EventListener;

use Manhattan\Bundle\ConsoleBundle\Event\ConfigureMenuEvent;

class ConfigureMenuListener
{
    /**
     * @param AGB\Bundle\ConsoleBundle\Event\ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $dropdown = $menu->addChild('Content', array('route'=>'console_content'))
            ->setLinkattribute('class', 'dropdown-toggle')
            ->setLinkattribute('data-toggle', 'dropdown')
            ->setAttribute('class', 'dropdown')
            ->setChildrenAttribute('class', 'menu-dropdown');

        $dropdown->addChild('Content', array('route' => 'console_content'))
            ->setLinkattribute('class', 'main');
        $dropdown->addChild('New Content', array('route' => 'console_content_new'));

    }
}
