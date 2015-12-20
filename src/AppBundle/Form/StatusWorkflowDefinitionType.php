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
        $allStatuses = StatusWorkflowDefinition::getAllStatuses();
        $builder
//            ->add(
//                'group',
//                'hidden',
//                [
//                    ''
//                ]
//            )
            ->add(
                'currentStatus',
                'hidden'
            )
            ->add(
                'nextStatus',
                'hidden'
            )
            ->add(
                'isMandatoryComment',
                'checkbox'
            )
            ->add(
                'is_allowed_to_switch',
                'checkbox',
                [
                    'mapped' => false,
                ]
            )
            ->add(
                'save',
                'submit'
            )
        ;
    }

//    public function configureOptions(OptionsResolver $resolver)
//    {
//        $resolver->setDefaults([
//            'data_class' => 'AppBundle\Entity\StatusWorkflowDefinition',
//        ]);
//    }
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
//        $view->
    }


    public function getName()
    {
        return 'status_workflow_definition';
    }
}