<?php

namespace Manhattan\Bundle\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;

class ContentType extends AbstractType
{
    private $choice_list;

    public function __construct(EntityChoiceList $choice_list = null)
    {
        $this->choice_list = $choice_list;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('body', 'markdown', array(
                'show_help' => true
            ))
            ->add('centerDownload', 'choice', array(
                'choices' => array(
                    0 => 'Sidebar Display',
                    1 => 'Main Content'
                ),
                'label' => 'Downloads Display Location'
            ))
            ->add('publishState', 'publish_state', array(
                'label' => 'Publish State'
            ))
        ;

        $options = array(
            'class' => 'Manhattan\Bundle\ContentBundle\Entity\Content',
            'empty_value' => '---',
            'required' => false,
            'label' => 'Parent Page'
        );
        if ($this->choice_list) {
            $options['choice_list'] = $this->choice_list;
        }
        $builder->add('parent', 'entity', $options);
    }

    public function getName()
    {
        return 'content';
    }

}
