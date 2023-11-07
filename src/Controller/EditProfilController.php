<?php

namespace App\Controller;

use App\Form\EditProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class EditProfilController extends AbstractController
{
    #[Route('/edit/profil', name: 'app_edit_profil')]
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();

        $form = $this->createForm(EditProfilType::class, $user);

        $form->handleRequest($request);
        dd($form->getData());

        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('uuid') != null && !$form->get('uuid')->isEmpty()) {
                try {
                    $user->setUuid($form->get("uuid")->getData());
                } catch (\Exception $e) {
                    $e->getMessage();
                }
            }
            if($form->get('image') != null && !$form->get('uuid')->isEmpty()) {
                try {
                    $user->setUuid($form->get("uuid")->getData());
                } catch (\Exception $e) {
                    $e->getMessage();
                }
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
