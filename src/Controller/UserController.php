<?php

namespace App\Controller;
use App\Form\UserType;
use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController

{
    /**
     * @Route("/user", name="user")
     */
    public function user(Request $request, ObjectManager $manager,
    UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $manager->persist($user);
            $manager->flush();
            $user->setPassword($hash);
        }

        return $this->render('user/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

}