<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use AppBundle\Entity\StatusWorkflowDefinition;
use AppBundle\Form\GroupStatusWorkflowDefinitionsType;
use AppBundle\Form\SelectGroupType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MatrixController
 * @Route(path="/matrix")
 */
class MatrixController extends Controller
{
    /**
     * @Route("/list/{groupId}")
     * @Template
     * @param Request $request
     * @param $groupId
     * @return array
     */
    public function listAction(Request $request, $groupId)
    {
        $selectedGroup = $this->getDoctrine()->getRepository('AppBundle:Group')->find($groupId);
        if (!$selectedGroup) {
            return new RedirectResponse($this->generateUrl('app_matrix_selectGroup'));
        }

        // this method affects $selectedGroup, don't move it
        $guess = GroupStatusWorkflowDefinitionsType::buildMatrix($selectedGroup);

        $form = $this->createForm(
            new GroupStatusWorkflowDefinitionsType(),
            $selectedGroup,
            [
                'action' => $this
                    ->generateUrl(
                        'app_matrix_list',
                        [
                            'groupId' => $selectedGroup->getId(),
                        ]
                    )
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $om = $this->getDoctrine()->getManager();

            // save data
            /** @var Group $group */
            $group = $form->getData();

            /** @var StatusWorkflowDefinition $statusWorkflowDefinition */
            foreach ($group->getStatusWorkflowDefinitions() as $statusWorkflowDefinition) {
                $statusWorkflowDefinition->setGroup($group);

                if ($statusWorkflowDefinition->isAllowedToSwitch()) {
                    $om->persist($statusWorkflowDefinition);
                } else {
                    $om->remove($statusWorkflowDefinition);
                }
            }

            $group->getStatusWorkflowDefinitions()->clear();
            $om->flush();
        }

        return [
            'form' => $form->createView(),
            'matrix' => $guess,
        ];
    }

    /**
     * @Route("/select-group")
     * @param Request $request
     * @return array
     * @Template("@App/Matrix/list.html.twig")
     */
    public function selectGroupAction(Request $request)
    {
        $selectGroupForm = $this->createForm(SelectGroupType::class);
        $selectGroupForm->handleRequest($request);
        if ($selectGroupForm->isSubmitted() && $selectGroupForm->isValid()) {
            /** @var Group $selectedGroup */
            $selectedGroup = $selectGroupForm->get('group')->getData();
            return $this->redirectToRoute('app_matrix_list', ['groupId' => $selectedGroup->getId(),]);
        }
        return ['select_group' => $selectGroupForm->createView(),];
    }
}