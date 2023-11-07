<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ImageRepository;
use App\Repository\CaliberRepository;
use App\Repository\WeaponRepository;

use App\Repository\VoteRepository;

class ListImageController extends AbstractController
{
    #[Route('/list/image/{weapon_id}', name: 'app_list_image')]
    public function index(VoteRepository $voteRepository,WeaponRepository $weaponRepository, Int $weapon_id,CaliberRepository $caliberRepository, ImageRepository $imageRepository): Response
    {
        return $this->render('list_image/index.html.twig', [
            'user' => $this->getUser(),
            'ImagesList' => $imageRepository->findBy( ['weapon' => $weapon_id]),
            'weapon' => $weaponRepository->findOneBy(['id'=>$weapon_id]),
            'caliber_list' => $caliberRepository->findAll(),
            'most_playable' => $weaponRepository->findMostPlayable(),
        ]);
    }

    #[Route('/image/one/{image_id}', name: 'app_image_one')]
    public function image(VoteRepository $voteRepository,WeaponRepository $weaponRepository, Int $image_id,CaliberRepository $caliberRepository, ImageRepository $imageRepository): Response
    {
        return $this->render('list_image/index.one.html.twig', [
            'user' => $this->getUser(),
            'image' => $imageRepository->findOneBy( ['id' => $image_id]),
            'weapon' => $weaponRepository->findOneBy( ['id' => $imageRepository->find($image_id)->getWeapon()]),
            'caliber_list' => $caliberRepository->findAll(),
            'most_playable' => $weaponRepository->findMostPlayable(),
        ]);
    }
}
