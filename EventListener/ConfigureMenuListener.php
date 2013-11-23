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

        $dropdown = $menu->addChild('Content', array('route'=>''))
            ->setLabelAttribute('class', 'pure-menu-heading')
            ->setChildrenAttribute('class', 'pure-menu-children green');

        $dropdown->addChild('Content Index', array('route' => 'console_content'))
            ->setLinkattribute('class', 'main');
        $dropdown->addChild('New Content', array('route' => 'console_content_new'));

    }
}
