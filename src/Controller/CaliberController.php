<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\WeaponRepository;
use App\Repository\CaliberRepository;


class CaliberController extends AbstractController
{
    #[Route('/caliber/{caliber_id}', name: 'app_caliber')]
    public function index(String $caliber_id, CaliberRepository $caliberRepository,WeaponRepository $weaponRepository): Response
    {
        // dd($weaponRepository->findByCaliber($caliber_id));
        return $this->render('caliber/index.html.twig', [
            'controller_name' => 'CaliberController',
            'caliber_list' => $caliberRepository->findAll(),
            'weapons_list' => $weaponRepository->findByCaliber($caliber_id)
        ]);
    }
}