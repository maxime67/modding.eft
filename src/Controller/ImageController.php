<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    #[Route('/image', name: 'app_image')]
    public function index(): response
    {
        return $this->render('image/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    public function AddImage()
    {
       
        $target_dir = "../public/images";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

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
        if (file_exists($target_file)) {
            $ext = explode('/', $_FILES["fileToUpload"]["name"])[0];
            rename($_FILES["fileToUpload"]["name"], rand().$ext);
        }
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
            return $this->render('home/index.html.twig', [
                'error_message' => "Seul les format jpg,png,jpeg sont acceptés",
            ]);
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                return $this->redirectToRoute('app_home');
            } else {
                return $this->redirectToRoute('app_home');
            }
        }
    }
}
