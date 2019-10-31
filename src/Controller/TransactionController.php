<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\TransactionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

class TransactionController extends AbstractController
{
    /**
     * Cryptocurrency transaction
     * @Route("/transaction", name="transaction")
     */
    public function index(Request $request, ObjectManager $manager)
    {
        $transaction = new Transaction();

        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($transaction);
            $manager->flush();
         
    }
        return $this->render('transaction/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
