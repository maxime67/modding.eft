<?php

namespace App\Controller;

use App\Repository\VoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


use App\Entity\Image;
use App\Entity\Vote;

use App\Repository\UserRepository;
use App\Repository\ImageRepository;

class VoteController extends AbstractController
{
    #[Route('/add/upvote/{image_id}', name: 'app_upvote')]
    public function AddUpVote(VoteRepository $voteRepository, ImageRepository $imageRepository, EntityManagerInterface $entity, int $image_id): Response
    {
        if (
            !$voteRepository->findBy(
                ['image' => $imageRepository->find($image_id), 'user' => $this->getUser()]
            )
        ) {
            $image = $imageRepository->find($image_id);
            $vote = new Vote();
            $vote->setImage($image);
            $vote->setType(1);
            $vote->setUser($this->getUser());
            $entity->persist($vote);
            $entity->flush();
        } else {
            return $this->redirectToRoute('app_home');
        }
        return $this->redirectToRoute('app_home');
    }

    #[Route('/add/downvote/{image_id}', name: 'app_downvote')]
    public function addDownVote(VoteRepository $voteRepository, ImageRepository $imageRepository, EntityManagerInterface $entity, int $image_id): Response
    {
        if (
            !$voteRepository->findBy(
                ['image' => $imageRepository->find($image_id), 'user' => $this->getUser()]
            )
        ) {
            $image = $imageRepository->find($image_id);
            $vote = new Vote();
            $vote->setImage($image);
            $vote->setType(0);
            $vote->setUser($this->getUser());
            $entity->persist($vote);
            $entity->flush();
        } else {
            return $this->redirectToRoute('app_home');
        }
        return $this->redirectToRoute('app_home');
    }
}
