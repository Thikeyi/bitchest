<?php

namespace App\Controller;

use App\Entity\Currency;
use App\Entity\CurrencyRate;

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
        $currencies = $repository->findAll();

        $repository = $this->getDoctrine()->getRepository(CurrencyRate::class);
        $currencyrates = $repository->findAll();

        return $this->render('admin_currency/index.html.twig', [
            'title' => 'Liste des crypto-monnaies',
            'currency' => $currencies,
            'currencyrate' => $currencyrates
            
            
        ]);
    }
}
