<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ImageRepository;
use App\Repository\CaliberRepository;
use App\Repository\WeaponRepository;

class ListImageController extends AbstractController
{
    #[Route('/list/image/{weapon_id}', name: 'app_list_image')]
    public function index(WeaponRepository $weaponRepository, Int $weapon_id,CaliberRepository $caliberRepository, ImageRepository $imageRepository): Response
    {
        return $this->render('list_image/index.html.twig', [
            'ImagesList' => $imageRepository->findBy( ['weapon' => $weapon_id]),
            'caliber_list' => $caliberRepository->findAll(),
            'most_playable' => $weaponRepository->findMostPlayable(),
        ]);
    }
}
