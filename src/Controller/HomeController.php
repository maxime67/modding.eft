<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\CaliberRepository;
use App\Repository\WeaponRepository;
class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(WeaponRepository $weaponRepository, CaliberRepository $caliberRepository): Response
    {
        // dd(phpinfo());
        return $this->render('home/index.html.twig', [
            'user' => $this->getUser(),
            'caliber_list' => $caliberRepository->findAll(),
            'most_playable' => $weaponRepository->findMostPlayable(),
        ]);
    }
}
