<?php

namespace App\DataFixtures;

use App\Entity\Currency;
use App\Entity\CurrencyRate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $currencies = [
            "Bitcoin",
            "Ethereum",
            "Ripple",
            "Bitcoin",
            "Cash",
            "Cardano",
            "Litecoin",
            "NEM",
            "Stellar",
            "IOTA",
            "Dash",
        ];

        foreach ($currencies as $currencyName) {
            $currency = new Currency();

            $currency->setName($currencyName);

            $firstCurrencyRate = new CurrencyRate();
            $firstCurrencyRate
                ->setPrice($this->getFirstCotation($currencyName))
                ->setDate(new \DateTime('yesterday'))    
            ;

            $currency->addCurrencyRate($firstCurrencyRate);

            $currencyRate = new CurrencyRate();
            $currencyRate
                ->setPrice($this->getFirstCotation($currencyName))
                ->setDate(new \DateTime())    
            ;

            $currency->addCurrencyRate($currencyRate);

            $manager->persist($firstCurrencyRate);            
            $manager->persist($currencyRate);            
            $manager->persist($currency);

        }

        $manager->flush();
    }

    private function getFirstCotation($cryptoname){
        return ord(substr($cryptoname,0,1)) + rand(0, 10);
    }
}
