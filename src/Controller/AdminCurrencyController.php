<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminCurrencyController extends AbstractController
{
    /**
     * @Route("/admin/currency", name="admin_currency")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Currency::class);
        $users = $repository->findAll();
        return $this->render('admin_currency/index.html.twig', [
            'title' => 'Liste des crypto-monnaies',
            'currency' => $currencies
        ]);
    }
}
