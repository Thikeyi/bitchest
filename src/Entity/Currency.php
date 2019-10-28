<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CurrencyRepository")
 */
class Currency
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\CurrencyRate", mappedBy="currency", cascade={"persist", "remove"})
     */
    private $currencyRate;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Transaction", mappedBy="currency", cascade={"persist", "remove"})
     */
    private $transaction;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserCurrency", mappedBy="currency")
     */
    private $userCurrencies;

    public function __construct()
    {
        $this->userCurrencies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCurrencyRate(): ?CurrencyRate
    {
        return $this->currencyRate;
    }

    public function setCurrencyRate(CurrencyRate $currencyRate): self
    {
        $this->currencyRate = $currencyRate;

        // set the owning side of the relation if necessary
        if ($this !== $currencyRate->getCurrency()) {
            $currencyRate->setCurrency($this);
        }

        return $this;
    }

    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    public function setTransaction(Transaction $transaction): self
    {
        $this->transaction = $transaction;

        // set the owning side of the relation if necessary
        if ($this !== $transaction->getCurrency()) {
            $transaction->setCurrency($this);
        }

        return $this;
    }

    /**
     * @return Collection|UserCurrency[]
     */
    public function getUserCurrencies(): Collection
    {
        return $this->userCurrencies;
    }

    public function addUserCurrency(UserCurrency $userCurrency): self
    {
        if (!$this->userCurrencies->contains($userCurrency)) {
            $this->userCurrencies[] = $userCurrency;
            $userCurrency->setCurrency($this);
        }

        return $this;
    }

    public function removeUserCurrency(UserCurrency $userCurrency): self
    {
        if ($this->userCurrencies->contains($userCurrency)) {
            $this->userCurrencies->removeElement($userCurrency);
            // set the owning side to null (unless already changed)
            if ($userCurrency->getCurrency() === $this) {
                $userCurrency->setCurrency(null);
            }
        }

        return $this;
    }
}
