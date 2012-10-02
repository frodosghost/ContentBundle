<?php

namespace AGB\Bundle\ContentBundle\Form\EventListener;

use Symfony\Component\Form\Event\DataEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormError;

class AddFileFieldSubscriber implements EventSubscriberInterface
{
    private $factory;

    public function __construct(FormFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::POST_BIND    => 'postBind'
        );
    }

    public function preSetData(DataEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        // check if the object is "new"
        if (!$data->getId()) {
            $form->add($this->factory->createNamed('file',
                'file', array(
                    'required' => true
            )));
        }
    }

    public function postBind(DataEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        // Check if the Form has a 'File' field
        if ($form->has('file')) {
            if (!$data->hasFile()) {
                $form->addError(new FormError('File must be given to create Document.'));
            }
        }
    }

}
