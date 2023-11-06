<?php

namespace App\Controller;

use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\CaliberRepository;
use App\Repository\WeaponRepository;

class UserController extends AbstractController
{
    #[Route('/user/{uuid}', name: 'app_user')]
    public function index(ImageRepository $imageRepository,CaliberRepository $caliberRepository, WeaponRepository $weaponRepository,String $uuid,UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(["uuid" => $uuid]);

        return $this->render('user/index.html.twig', [
            'userWeapons' => $user->getImages(),
            'caliber_list' => $caliberRepository->findAll(),
            'most_playable' => $weaponRepository->findMostPlayable(),
            'User' => $user,
        ]);
    }
}
