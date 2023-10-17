<?php

namespace App\Controller;

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




class ImageController extends AbstractController
{
    #[Route('/image/{weapon_id}', name: 'app_image')]
    public function index(int $weapon_id, EntityManagerInterface $entityManager, CaliberRepository $caliberRepository, WeaponRepository $weaponRepository): Response
    {
        return $this->render('image/index.html.twig', [
            'caliber_list' => $caliberRepository->findAll(),
            'weapon' => $weaponRepository->find($weapon_id),
        ]);
    }

    #[Route('/image/add/{weapon_id}', name: 'app_image_add')]
    public function AddImage(EntityManagerInterface $entityManager, WeaponRepository $weaponRepository, int $weapon_id)
    {
        $target_dir = "../public/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $name = rand().".".$imageFileType;

        $image = new Image();
        $image->setUrl($name);
        $image->setWeapon($weaponRepository->find($weapon_id));


        // Check if image file is a actual image or fake image
        // if (isset($_POST["submit"])) {
        // dd($_FILES["fileToUpload"]["name"]);
        // $check = getimagesize($_FILES["fileToUpload"]["name"]);
        // if ($check !== false) {
        //     $uploadOk = 1;
        // } else {
        //     return $this->redirectToRoute('app_home');
        // }
        // }
        // Check file size
        // if ($_FILES["fileToUpload"]["size"] > 500000) {
        //     $uploadOk = 0;
        // } else {
        //     return $this->render('home/index.html.twig', [
        //         'error_message' => "Fichier trop volumineux",
        //     ]);
        // }

        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        ) {
            $uploadOk = 0;
            dd("format error");
            return $this->render('home/index.html.twig', [
                'error_message' => "Seul les format jpg,png,jpeg sont acceptés",
            ]);
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                rename('../public/' . $_FILES["fileToUpload"]["name"], $name);
                $entityManager->persist($image);
                $entityManager->flush();
                // dd("SUCCES");
                return $this->redirectToRoute('app_home');
            } else {
                dd("c'est chiant");
                return $this->redirectToRoute('app_home');
            }
        }
    }
}