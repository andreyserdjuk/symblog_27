<?php

namespace AppBundle\Controller;

use AppBundle\Form\LoginType;
use AppBundle\Form\Model\Login;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    public function loginAction()
    {
        $authUtils = $this->get('security.authentication_utils');
        $lastAuthError = $authUtils->getLastAuthenticationError();
        $lastAuthUser = $authUtils->getLastUsername();

        $form = $this->createForm(
            new LoginType(),
            new Login(),
            ['action' => $this->generateUrl('login_check')]
        );

        return $this->render('AppBundle:Account:register.html.twig', array(
            'form' => $form->createView()
        ));

//        return $this->render('AppBundle:Security:login.html.twig', array(
//                'last_auth_error' => $lastAuthError,
//                'last_auth_user' => $lastAuthUser,
//            ));
    }

    public function loginCheckAction(Request $request)
    {
        $form = $this->createForm(
            new LoginType(),
            new Login(),
            ['action' => $this->generateUrl('login_check')]
        );

        $form->handleRequest($request);
        $formData = '';
        if ($form->isValid()) {
            $formData = $form->getData();
        }

        return $this->render('AppBundle:Security:loginCheck.html.twig', array(
            'form_data' => $formData
            ));
    }

}
