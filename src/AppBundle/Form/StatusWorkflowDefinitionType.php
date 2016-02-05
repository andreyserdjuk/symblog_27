<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatusWorkflowDefinitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'currentStatus',
                HiddenType::class
            )
            ->add(
                'nextStatus',
                HiddenType::class
            )
            ->add(
                'allowed_to_switch',
                CheckboxType::class,
                [
                    'label' => false,
                    'required' => false,
                ]
            )
            ->add(
                'isMandatoryComment',
                CheckboxType::class,
                [
                    'label' => false,
                    'required' => false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => 'AppBundle\Entity\StatusWorkflowDefinition',
        ]);
    }

    public function getName()
    {
        return 'status_workflow_definition';
    }
}