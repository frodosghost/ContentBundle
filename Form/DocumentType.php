<?php

namespace Manhattan\Bundle\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Manhattan\Bundle\ContentBundle\Form\EventListener\AddFileFieldSubscriber;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description', 'textarea', array(
                'label' => 'Description'
            ))
        ;

        $subscriber = new AddFileFieldSubscriber($builder->getFormFactory());
        $builder->addEventSubscriber($subscriber);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manhattan\Bundle\ContentBundle\Entity\Document'
        ));
    }

    public function getName()
    {
        return 'document';
    }
}
