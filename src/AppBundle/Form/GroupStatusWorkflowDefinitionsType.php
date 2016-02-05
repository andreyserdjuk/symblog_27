<?php

namespace AppBundle\Form;

use AppBundle\Entity\Group;
use AppBundle\Entity\StatusWorkflowDefinition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupStatusWorkflowDefinitionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'status_workflow_definitions',
                CollectionType::class,
                [
                    'entry_type' => StatusWorkflowDefinitionType::class,
                    'label' => false,
                ]
            )
            ->add(
                'submit',
                SubmitType::class
            )
        ;
}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Group',
            'csrf_protection' => false,
        ]);
    }

    public function getName()
    {
        return 'group_status_workflow_definitions_matrix';
    }

    public static function buildMatrix (Group $selectedGroup) {
        $defsMap = [];
        /** @var StatusWorkflowDefinition $savedDef */
        foreach ($selectedGroup->getStatusWorkflowDefinitions() as $savedDef) {
            $defsMap[$savedDef->getCurrentStatus()][$savedDef->getNextStatus()] = $savedDef;
            $savedDef->setAllowedToSwitch(true);
        }

        $guess = [];
        foreach (StatusWorkflowDefinition::getAllStatuses() as $k1 => $v1) {
            foreach (StatusWorkflowDefinition::getAllStatuses() as $k2 => $v2) {
                if (isset($defsMap[$k1][$k2])) {
                    $guess[$k1][$k2] = $defsMap[$k1][$k2];
                } else {
                    $statusWorkflowDef = new StatusWorkflowDefinition();
                    $statusWorkflowDef->setCurrentStatus($k1);
                    $statusWorkflowDef->setNextStatus($k2);
                    $statusWorkflowDef->setIsMandatoryComment(false);
                    $statusWorkflowDef->setAllowedToSwitch(false);
                    $statusWorkflowDef->setGroup($selectedGroup);
                    $guess[$k1][$k2] = $statusWorkflowDef;
                    $selectedGroup->setStatusWorkflowDefinition($statusWorkflowDef);
                }
            }
        }

        return $guess;
    }
}