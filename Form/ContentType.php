<?php

namespace Manhattan\Bundle\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ContentType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('body', 'textarea', array(
                'attr'  => array(
                    'class' => 'tinymce',
                    'data-theme' => 'body'
                ), 'required' => false
            ))
        ;
    }

    public function getName()
    {
        return 'content';
    }
}
