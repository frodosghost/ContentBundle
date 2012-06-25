<?php

namespace Manhattan\Bundle\ContentBundle\EventListener;

use Manhattan\Bundle\ConsoleBundle\Event\ConfigureMenuEvent;

class ConfigureMenuListener
{
    /**
     * @param Manhattan\Bundle\ConsoleBundle\Event\ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $dropdown = $menu->addChild('Content', array('route'=>'content'))
            ->setLinkattribute('class', 'dropdown-toggle')
            ->setLinkattribute('data-toggle', 'dropdown')
            ->setAttribute('class', 'dropdown')
            ->setChildrenAttribute('class', 'menu-dropdown');

        $dropdown->addChild('Content', array('route' => 'content'));
        $dropdown->addChild('New Content', array('route' => 'content_new'));

    }
}
