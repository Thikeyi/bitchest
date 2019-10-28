<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 
 *@UniqueEntity(fields={"email"},
 *message="Il existe déjà un utilisateur avec cet email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire minimum 8 caractères")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserCurrency", mappedBy="user")
     */
    private $userCurrencies;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="user")
     */
    private $transactions;

    public function __construct()
    {
        $this->userCurrencies = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }


    public function getUsername(): ?string
    {
        return $this->email;
    }


    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

     public function eraseCredentials() {}

     public function getSalt() {}

     public function getRoles() {
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
             $userCurrency->setUser($this);
         }

         return $this;
     }

     public function removeUserCurrency(UserCurrency $userCurrency): self
     {
         if ($this->userCurrencies->contains($userCurrency)) {
             $this->userCurrencies->removeElement($userCurrency);
             // set the owning side to null (unless already changed)
             if ($userCurrency->getUser() === $this) {
                 $userCurrency->setUser(null);
             }
         }

         return $this;
     }

     /**
      * @return Collection|Transaction[]
      */
     public function getTransactions(): Collection
     {
         return $this->transactions;
     }

     public function addTransaction(Transaction $transaction): self
     {
         if (!$this->transactions->contains($transaction)) {
             $this->transactions[] = $transaction;
             $transaction->setUser($this);
         }

         return $this;
     }

     public function removeTransaction(Transaction $transaction): self
     {
         if ($this->transactions->contains($transaction)) {
             $this->transactions->removeElement($transaction);
             // set the owning side to null (unless already changed)
             if ($transaction->getUser() === $this) {
                 $transaction->setUser(null);
             }
         }

         return $this;
     }
}