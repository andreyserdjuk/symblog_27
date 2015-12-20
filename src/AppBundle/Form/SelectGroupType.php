<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SelectGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'group',
                'entity',
                [
                    'class' => 'AppBundle\Entity\Group',
                    'choice_label' => 'name',
                ]
            )
            ->add(
                'get_matrix',
                'submit',
                [
                    'label' => 'get matrix',
                ]
            )
        ;
    }

    public function getName()
    {
        return 'select_group';
    }
}