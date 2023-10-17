<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;


use App\Service\FileUploader;

use App\Entity\Weapon;
use App\Repository\WeaponRepository;
use App\Entity\Image;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\CaliberRepository;
use Doctrine\ORM\EntityManagerInterface;




class ImageController extends AbstractController
{
    #[Route('/image/{weapon_id}', name: 'app_image')]
    public function index(EntityManagerInterface $entityManager,CaliberRepository $caliberRepository): Response
    {
        return $this->render('image/index.html.twig', [
            'caliber_list' => $caliberRepository->findAll(),
        ]);
    }

    #[Route('/image/add/{weapon_id}', name: 'app_image_add')]
    public function AddImage(EntityManagerInterface $entityManager,WeaponRepository $weaponRepository, int $weapon_id)
    {
        $target_dir = "../public/images/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $ext = explode('/', $_FILES["fileToUpload"]["name"])[0];
        $image = new Image();
        $image->setUrl($_FILES["fileToUpload"]["name"]);
        $image->setWeapon($weaponRepository->find($weapon_id));

        // Check if image file is a actual image or fake image
        // if (isset($_POST["submit"])) {
        //     $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        //     if ($check !== false) {
        //         $uploadOk = 1;
        //     } else {
        //         $uploadOk = 0;
        //         return $this->render('home/index.html.twig', [ 
        //         ]);
        //     }
        // }

        // Check if file already exists
        // if (file_exists($target_file)) {
        //     dd($_FILES["fileToUpload"]["name"]);
        //     rename('../public/images'.$_FILES["fileToUpload"]["name"], rand() . $ext);
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
                $entityManager->persist($image);
                $entityManager->flush();
                // dd("SUCCES");
                return $this->redirectToRoute('app_home');
            } else {
                dd('error');
                return $this->redirectToRoute('app_home');
            }
        }
    }
}