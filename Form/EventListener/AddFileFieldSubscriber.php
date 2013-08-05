<?php

namespace Manhattan\Bundle\ContentBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
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

    /**
     * Adds the file field to the form if create form.
     *
     * @param  FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        // check if the object is "new"
        if (!$data->getId()) {
            $form->add('file', 'file', array(
                'required' => true,
                'data_class' => 'Manhattan\Bundle\ContentBundle\Entity\Document'
            ));
        }
    }

    /**
     * Determines if the file has been provided, and displays error if not provided.
     *
     * @param  FormEvent $event
     */
    public function postBind(FormEvent $event)
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
