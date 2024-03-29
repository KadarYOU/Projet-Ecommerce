<?php

namespace App\Controller;

use App\Form\LoginTypePhpType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $utils, Request $request)
    {
        $form = $this->createForm(LoginTypePhpType::class);
        // dump($utils->getLastAuthenticationError(), $utils->getLastUsername());
        // dd($utils->getLastAuthenticationError());
        // dd($request);
        return $this->render('security/login.html.twig', [
            'formView' => $form->createView(),
            'error' => $utils->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/logout",name="security_logout")
     */
    public function logout()
    {
    }
}
