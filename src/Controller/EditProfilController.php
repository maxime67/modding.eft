<?php

namespace App\Controller;

use App\Form\EditProfilType;
use ContainerEzl6qEV\getSluggerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;


class EditProfilController extends AbstractController
{
    #[Route('/edit/profil', name: 'app_edit_profil')]
    public function editProfil(SluggerInterface $slugger,Request $request,EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(EditProfilType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            dd($form->get("image")->getData());
            $uuid = $form->get("uuid");
            if($uuid != null) {
                try {
                    $user->setUuid($form->get("uuid")->getData());
                } catch (\Exception $e) {
                    dd($e->getMessage());
                }
            }
            $imagefile = $form->get('image')->getData();
                if ($imagefile) {
                    if($this->getUser()->getUrlAvatar() != "default.png"){
                        $filesystem = new Filesystem();
                        try{
                            $filesystem->remove($user->getUrlAvatar());
                        } catch (\Exception $e){
                            dd($e->getMessage());
                        }
                    }
                    $originalFilename = pathinfo($imagefile->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$imagefile->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $imagefile->move(
                            $this->getParameter('avatars_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        dd($e->getMessage());
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $user->setUrlAvatar($newFilename);
            }
            $entityManager->persist($user);
            $entityManager->flush();
            // ... perform some action, such as saving the task to the database
            return $this->redirectToRoute('app_home');
        }
//        dd($form->createView());
        return $this->render('edit_profil/index.html.twig', [
            'user' => $this->getUser(),
            'currentUser' => $user,
            'form' => $form->createView(),
        ]);
    }
}
