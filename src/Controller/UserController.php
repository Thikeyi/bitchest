<?php

namespace App\Controller;
use App\Entity\Currency;
use App\Entity\CurrencyRate;
use App\Entity\Transaction;
use App\Entity\UserCurrency;
use App\Form\TransactionType;
use App\Form\UserType;
use App\Entity\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController

{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/user", name="user")
     */
    public function user()
    {
        // liste des crypto monnaies coté utilisateur
        $repository = $this->getDoctrine()->getRepository(Currency::class);
        $currencies = $repository->findAll();

        return $this->render('user/index.html.twig', [
            'title' => 'Liste des crypto-monnaies',
            'currencies' => $currencies
        ]);
    }

    /**
     * @Route("/user/wallet", name="user_wallet")
     */
    public function wallet()
    {
        // l'utilisateur connecté
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository(UserCurrency::class);

        // filtre sur l'utilisateur connecté
        $wallet = $repository->findBy(array (
            'user'=>$user
        ));


        $balance = 0;

        foreach ($wallet as $userCurrency) {
            $balance += $userCurrency->getQuantity() * $userCurrency->getCurrency()->getPrice();
        }


        return $this->render('user/wallet.html.twig', [
            'wallet' => $wallet,
            'balance' => $balance
        ]);
    }

    /**
     * @Route("/user/sell/{id}", name="user_sell")
     *
     */
    public function sell(Request $request, UserCurrency $userCurrency)
    {
        $manager = $this->getDoctrine()->getManager();

        // détail d'une transaction
        $transaction = new Transaction();
        $transaction->setType('sale')
            ->setUser($this->getUser())
            ->setCurrency($userCurrency->getCurrency())
            ->setQuantity($userCurrency->getQuantity())
            ->setPrice($userCurrency->getCurrency()->getPrice())
            ->setAmount($userCurrency->getCurrency()->getPrice() * $userCurrency->getQuantity())
        ;


        $manager->persist($transaction);
        $manager->remove($userCurrency);
        $manager->flush();
        $this->addFlash('success', 'Vente réalisée avec succès!');


        return $this->redirectToRoute('user_wallet');
    }


    /**
     * @Route("/user/buy/{id}", name="user_buy")
     */
    public function buy(Request $request, ObjectManager $manager, Currency $currency)
    {
        //Achat d'une crypto-monnaie
        if ($request->isMethod('POST')) {
            // $_POST['quantity']
            $quantity = $request->request->get('quantity');

            $repository = $manager->getRepository(UserCurrency::class);
            $user = $this->getUser();

            // Filtre sur l'utilisateur et la monnaie
            $userCurrency = $repository->findOneBy(array (
                'user'=>$user,
                'currency' => $currency
            ));
            // Si l'utilisateur ne possède pas encore cette monnaie on crée une ligne dans Usercurrency
            if (is_null($userCurrency)) {
                $userCurrency = new UserCurrency();
                $userCurrency
                    ->setUser($user)
                    ->setCurrency($currency)
                    ->setQuantity($quantity)
                ;

                // Si l'utilisateur possede déjà cette monnaie on met à jour la quantité
            } else {
                $currentQuantity = $userCurrency->getQuantity();
                $userCurrency
                    ->setQuantity($currentQuantity + $quantity)
                ;

            }

                 // Détail d'une transaction
                 $transaction = new Transaction();
                 $transaction->setType('purchase')
                     ->setUser($this->getUser())
                     ->setCurrency($currency)
                     ->setQuantity($quantity)
                     ->setPrice($currency->getPrice())
                     ->setAmount($currency->getPrice() * $quantity)
                 ;


                $manager->persist($userCurrency);
                $manager->persist($transaction);
                $manager->flush();
                $this->addFlash('success', 'Transaction réussie !');

            return $this->redirectToRoute('user_wallet');

        }

        return $this->render('user/buy.html.twig', [
            'currency'=>$currency
        ]);
    }


    /**
     *
     * @Route("/user/historic/", name="user_historic")
     */
    public function historic()
    {
        //historique des transactions de l'utilisateur filtrées par date
        $repository = $this->getDoctrine()->getRepository(Transaction::class);
        $transactions = $repository->findBy([], ['transactionDate' => 'DESC']);

        return $this->render('user/historic.html.twig', [
            'transactions'=>$transactions
        ]);


    }
    /**
     *
     * @Route("/user/rate/{id}", name="user_rate")
     */
    public function rate(Currency $currency)
    {
        //fluctuation d'une crypto-monnaie filtrée par date
        $repository = $this->getDoctrine()->getRepository(CurrencyRate::class);
        $rates = $repository->findBy(['currency' => $currency], ['date' => 'ASC']);

        /*
        data: {
        labels: ['Bitcoin', 'Ethereum', 'Ripple', 'Bitcoin Cash', 'Cardano', 'Litecoin', 'NEM', 'Stellar', 'IOTA', 'Dash'],

        datasets: [{
            label: 'Cours',
            data: [12, 19, 3, 5, 2, 3],
        }]
    }
         */

        $data = ['labels' => [], 'datasets' => []];

        $values = [];

        foreach ($rates as $rate) {
            $data['labels'][] = $rate->getDate()->format('d/m/y');
            $values[] =  $rate->getPrice();
        }

        $data['datasets'][] = ['label' => 'Cours du ' . $currency->getName(), 'data' => $values];

        return $this->render('user/rate.html.twig', [
            'rates'=>$rates,
            'currency'=>$currency,
            'json_rates' => json_encode($data)
        ]);

    }
}