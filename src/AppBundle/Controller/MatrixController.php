<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use AppBundle\Entity\StatusWorkflowDefinition;
use AppBundle\Form\GroupStatusWorkflowDefinitionsType;
use AppBundle\Form\SelectGroupType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
     * @Route("/list")
     * @Template
     * @param Request $request
     * @return array Render with two forms:
     *
     * Render with two forms:
     * - select user - if submitted, the second will be rendered below:
     * - status workflow definitions matrix - rendered for the special group.
     *
     * 1. request - show groups list
     * 2. r - select and submit group => build status workflow definitions matrix
     * 3. r - submit status workflow definitions matrix => draw saved matrix form
     */
    public function listAction(Request $request)
    {
        $selectGroupForm = $this->createForm(new SelectGroupType());
        $selectGroupForm->handleRequest($request);

        // if we got group, we can setup
        if ($selectGroupForm->isSubmitted() && $selectGroupForm->isValid()) {
            /** @var Group $selectedGroup */
            $selectedGroup = $selectGroupForm->get('group')->getData();
        } else {
            return ['select_group' => $selectGroupForm->createView()];
        }

        $defsMap = [];
        /** @var StatusWorkflowDefinition $savedDef */
        foreach ($selectedGroup->getStatusWorkflowDefinitions() as $savedDef) {
            $defsMap[$savedDef->getCurrentStatus()][$savedDef->getNextStatus()] = true;
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
                    $statusWorkflowDef->setIsMandatoryComment(null);
                    $guess[$k1][$k2] = $statusWorkflowDef;
                }
            }
        }

        $wf = $this->createForm(
            new GroupStatusWorkflowDefinitionsType(),
            null,
            ['action' => $this->generateUrl('app_matrix_save')]
        );
        $wf->handleRequest($request);
        if ($wf->isValid() && $wf->isSubmitted()) {
            var_dump($wf->getData());exit;
        }

        return [
            'form_status_workflow_defs' => $wf->createView(),
            'select_group' => $selectGroupForm->createView(),
            'matrix' => $guess,
        ];
    }

    /**
     * @Route(path="/save", name="app_matrix_save")
     * @Method("POST")
     * @param Request $request
     * @return array
     */
    public function saveAction(Request $request)
    {
        $wf = $this->createForm(new GroupStatusWorkflowDefinitionsType(), new Group('some_name', []), ['action' => $this->generateUrl('app_matrix_save')]);
        $wf->handleRequest($request);
        if ($wf->isSubmitted() && $wf->isValid()) {
            var_dump($wf->getData());exit;
        }
        return new RedirectResponse($this->generateUrl('app_matrix_list'));
    }
}