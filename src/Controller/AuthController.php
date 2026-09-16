<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    #[Route('/auth', name: 'app_auth')]
    public function index(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Prepare registration form view as well
        $user = new User();
        $registrationForm = $this->createForm(RegistrationFormType::class, $user);

        return $this->render('auth/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'registrationForm' => $registrationForm->createView(),
        ]);
    }
}