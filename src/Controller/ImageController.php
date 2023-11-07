<?php

namespace App\Controller;

use App\Form\ImageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use App\Entity\Weapon;
use App\Repository\WeaponRepository;
use App\Entity\Image;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\CaliberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;







class ImageController extends AbstractController
{
    #[Route('/image/new/{weapon_id}', name: 'app_product_new')]
    public function new(Int $weapon_id, EntityManagerInterface $entity, WeaponRepository $weaponRepository, CaliberRepository $caliberRepository, Request $request, SluggerInterface $slugger): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imagefile */
            $imagefile = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imagefile) {
                $originalFilename = pathinfo($imagefile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imagefile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imagefile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    dd($e->getMessage());
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $image->setUrl($newFilename);
            }
            $image = new Image();
            $image->setUrl($newFilename);
            $image->setUser($this->getUser());
            $image->setWeapon($weaponRepository->findOneBy(['id'=> $weapon_id]));

            $entity->persist($image);
            $entity->flush();
            return $this->redirectToRoute('app_home');
        }
        // dd($form);

        return $this->render('image/new.html.twig', [
            'weapon' => $weaponRepository->find($weapon_id),
            'user' => $this->getUser(),
            'form' => $form,
            'caliber_list' => $caliberRepository->findAll(),
            'most_playable' => $weaponRepository->findMostPlayable(),
        ]);
    }

    #[Route('/image/{weapon_id}', name: 'app_image')]
    public function index(int $weapon_id, EntityManagerInterface $entityManager, CaliberRepository $caliberRepository, WeaponRepository $weaponRepository): Response
    {
        return $this->render('image/index.html.twig', [
            'user' => $this->getUser(),
            'caliber_list' => $caliberRepository->findAll(),
            'weapon' => $weaponRepository->find($weapon_id),
            'most_playable' => $weaponRepository->findMostPlayable(),
        ]);
    }
}