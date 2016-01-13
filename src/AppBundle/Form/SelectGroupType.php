<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SelectGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'group',
                EntityType::class,
                [
                    'class' => 'AppBundle\Entity\Group',
                    'choice_label' => 'name',
                ]
            )
            ->add(
                'get_matrix',
                SubmitType::class,
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