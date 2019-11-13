<?php

namespace App\Controller;

use App\Entity\Currency;
use App\Entity\CurrencyRate;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminCurrencyController extends AbstractController
{
    /**
     *
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/currency", name="admin_currency")
     */
    public function index()
    {
        //liste des crypto-monnaies et leurs cours accessible aux Admins
        $repository = $this->getDoctrine()->getRepository(Currency::class);
        $currencies = $repository->findAll();


        return $this->render('admin_currency/index.html.twig', [
            'title' => 'Liste des crypto-monnaies',
            'currencies' => $currencies,
          
            
            
            
        ]);
    }
    
}
