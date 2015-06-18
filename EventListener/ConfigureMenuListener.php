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

        // Main Menu Item
        $dropdown = $menu->addChild('Content', array(
            'route' => 'console_content',
            'icon' => 'file',
            'inverted' => false,
            'append' => false,
            'dropdown' => true,
            'caret' => true
        ));
        $dropdown->addChild('Content Index', array('route' => 'console_content'));
        $dropdown->addChild('New Content', array('route' => 'console_content_new'));
    }
}
