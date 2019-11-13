<?php

namespace App\DataFixtures;

use App\Entity\Currency;
use App\Entity\CurrencyRate;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $currencies = [
            "Bitcoin",
            "Ethereum",
            "Ripple",
            "Bitcoin Cash",
            "Cardano",
            "Litecoin",
            "NEM",
            "Stellar",
            "IOTA",
            "Dash",
        ];

        foreach ($currencies as $currencyName) {
            $currency = new Currency();

            $currency->setName($currencyName)
                 ->setPrice($this->getFirstCotation($currencyName))
                ->setLogo(str_replace(' ', '-', strtolower($currencyName)) . '.png');

            $firstCurrencyRate = new CurrencyRate();
            $currentCotation = $this->getFirstCotation($currencyName);

            $firstCurrencyRate
                ->setPrice($currentCotation)
                ->setDate(new \DateTime('-30days'))
            ;

            $currency->addCurrencyRate($firstCurrencyRate);

            $manager->persist($firstCurrencyRate);

            for ($i = 29; $i >= 1; $i--) {
                $currentCurrencyRate = new CurrencyRate();
                $currentCotation = $currentCotation + $this->getCotationFor($currencyName);
                $currentCurrencyRate
                    ->setPrice($currentCotation)
                    ->setDate(new \DateTime('-' . $i . 'days'))
                ;

                $currency->addCurrencyRate($currentCurrencyRate);

                $manager->persist($currentCurrencyRate);

            }


            $lastCurrencyRate = new CurrencyRate();
            $lastCotation = $currentCotation + $this->getCotationFor($currencyName);


            $lastCurrencyRate
                ->setPrice($lastCotation)
                ->setDate(new \DateTime())
            ;

            $currency->addCurrencyRate($lastCurrencyRate);

            $manager->persist($lastCurrencyRate);
            $manager->persist($currency);

        }

        $admin = new User();

        $admin
            ->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setEmail('admin@bitchest.com')
            ->setAddress($faker->address)
            ->setCity($faker->city)
            ->setCountry('France')
            ->setPhoneNumber($faker->phoneNumber)
            ->setZipcode($faker->randomNumber(5))
            ->setRole('ROLE_ADMIN')
            ->setPassword($this->encoder->encodePassword($admin, 'admin'))
        ;

        $manager->persist($admin);

        $user = new User();

        $user
            ->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setEmail('user@bitchest.com')
            ->setAddress($faker->address)
            ->setCity($faker->city)
            ->setCountry('France')
            ->setPhoneNumber($faker->phoneNumber)
            ->setZipcode($faker->randomNumber(5))
            ->setRole('ROLE_USER')
            ->setPassword($this->encoder->encodePassword($user, 'user'))
        ;

        $manager->persist($user);

        $manager->flush();
    }

    private function getFirstCotation($cryptoname){
        return ord(substr($cryptoname,0,1)) + rand(0, 10);
    }

    /**
     * Renvoie la variation de cotation de la crypto monnaie sur un jour
     * @param $cryptoname {string} Le nom de la crypto monnaie
     */
    private function getCotationFor($cryptoname){
        return ((rand(0, 99)>40) ? 1 : -1) * ((rand(0, 99)>49) ? ord(substr($cryptoname,0,1)) : ord(substr($cryptoname,-1))) * (rand(1,10) * .01);
    }
}
