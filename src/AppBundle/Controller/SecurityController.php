<?php

namespace AppBundle\Controller;

use AppBundle\Form\LoginType;
use AppBundle\Form\Model\Login;
use Blogger\BlogBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login_route")
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

        return $this->render('AppBundle:Security:login.html.twig', [
            'last_auth_error' => $lastAuthError,
            'last_auth_user' => $lastAuthUser,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login_check", name="login_check")
     */
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

        return $this->render('AppBundle:Security:loginCheck.html.twig', [
            'form_data' => $formData
        ]);
    }

}
