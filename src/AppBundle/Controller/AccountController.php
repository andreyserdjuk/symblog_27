<?php

namespace AppBundle\Controller;

use AppBundle\Form\Model\Registration;
use AppBundle\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends Controller
{
    public function registerAction()
    {
        $form = $this->createForm(
            new RegistrationType(),
            new Registration(),
            ['action' => $this->generateUrl('account_create')]
        );

        return $this->render('AppBundle:Account:register.html.twig', array(
                'form' => $form->createView()
            ));    
    }

    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new RegistrationType(), new Registration(), ['action' => $this->generateUrl('account_create')]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $registration = $form->getData();

            $em->persist($registration->getUser());
            $em->flush();

            return $this->redirectToRoute('blogger_blog_homepage');
        }

        return $this->redirectToRoute('register');
    }
}
