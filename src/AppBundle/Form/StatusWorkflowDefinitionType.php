<?php

namespace AppBundle\Form;


use AppBundle\Entity\StatusWorkflowDefinition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatusWorkflowDefinitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $builder
            ->add(
                'currentStatus',
                'hidden'
            )
            ->add(
                'nextStatus',
                'hidden'
            )
            ->add(
                'allowed_to_switch',
                'checkbox',
                [
                    'label' => false,
                    'required' => false,
                ]
            )
            ->add(
                'isMandatoryComment',
                'checkbox',
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