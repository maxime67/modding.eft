<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Repository\CaliberRepository;
use App\Repository\WeaponRepository;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(CaliberRepository $caliberRepository, WeaponRepository $weaponRepository, AuthenticationUtils $authenticationUtils): Response
    {
        /**
         * This method renders the login page with the last username entered and any authentication errors.
         * It also retrieves a list of all calibers and the most playable weapon from the database.
         *
         * @param CaliberRepository $caliberRepository The repository for the Caliber entity.
         * @param WeaponRepository $weaponRepository The repository for the Weapon entity.
         * @param AuthenticationUtils $authenticationUtils The service for handling authentication-related tasks.
         *
         * @return Response The rendered login page.
         */
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'caliber_list' => $caliberRepository->findAll(),
            'most_playable' => $weaponRepository->findMostPlayable(),
        ]);
    }

    
    
}