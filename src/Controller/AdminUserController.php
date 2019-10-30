<?php

namespace App\Controller;
use App\Form\UserType;
use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/user", name="admin_user")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();
        return $this->render('admin_user/index.html.twig', [
            'title' => 'Liste des utilisateurs',
            'users' => $users
        ]);
    }

    /**
     * @Route("/edition/{id}", defaults={"id": null}, requirements={"id": "\d+"}, name="admin_user_create")
     * 
     */
    public function edit(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if (is_null($id)) { 
            $user = new User();
        } else { 
            $user = $em->find(User::class, $id);
           
        if (is_null($user)) {
            throw new NotFoundHttpException();
            }
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
      
        if ($form->isSubmitted()) {
        if ($form->isValid()) {
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'L\'utilisateur est enregistré');

            return $this->redirectToRoute('admin_user/index.html.twig');

            } else {
               
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }
        return $this->render(
            'admin_user/create.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }


    /**
     * @Route("/suppression/{id}", name="admin_user_delete"))
     */
    public function delete(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$user->getUsers()->isEmpty()) {
            $this->addFlash(
                'error',
                'L\'utilisateur ne peut être supprimé'
            );
        } else {
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'L\'utilisateur est supprimé');
        }
        return $this->redirectToRoute('admin_user/index.html.twig');
    }
}