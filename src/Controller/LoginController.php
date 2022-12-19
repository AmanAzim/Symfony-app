<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authUtils): Response // this AuthenticationUtils class give access to methods like where we can check the last tried and failed login credential
    {
        $lastUserEmail = $authUtils->getLastUsername();
        $error = $authUtils->getLastAuthenticationError();

        return $this->render('login/index.html.twig', [
            'lastUserEmail' => $lastUserEmail,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        // as this path is defined in security.yaml symfony will take care of the rest
    }

}
