<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Klasa definiująca użytkownika systemu
 * 
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="E-mail znajduje się już w bazie.")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=90)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=90)
     * @var string
     */    
    private $surname;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @var array
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     * @var string The hashed password
     */
    private $password;

    /**
     * @ORM\Column(type="boolean", options={"default":0})
     * @var boolean
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="boolean", options={"default":0})
     * @var boolean
     */
    private $isDisabled = false;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var \Datetime
     */    
    private $user_created_at;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var \Datetime
     */    
    private $user_updated_at;

    
    /**
     * ID użytkownika
     * @return int|null
     */
    public function getId(): ?int{
        return $this->id;
    }

    /**
     * Zwracam imię usera
     * @return  string
     */ 
    public function getName(): ?string {
        return $this->name;
    }

    /**
     * Ustawiam imię użytkownika
     * @param string $name
     * @return  self
     */ 
    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    /**
     * Zwracam nazwisko użytkownika
     * @return string|null
     */
    public function getSurname(): ?string {
        return $this->surname;
    }

    /**
     * Ustawiam nazwisko użytkownika
     * @param string $surname
     * @return  self
     */ 
    public function setSurname(string $surname){
        $this->surname = $surname;
        return $this;
    }

    /**
     * Email użytkownika 
     * @return string|null
     */
    public function getEmail(): ?string {
        return $this->email;
    }

    /**
     * Ustawiam adres użytkownika
     * @param string $email
     * @return self
     */
    public function setEmail(string $email): self{
        $this->email = $email;
        return $this;
    }

    /**
     * Pobieram role dla użytkownika
     * @see UserInterface
     * @return array
     */
    public function getRoles(): array{
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    /**
     * Ustawiam role dla użytkownika
     * @param array $roles
     * @return self
     */
    public function setRoles(array $roles): self{
        $this->roles = $roles;
        return $this;
    }

    /**
     * Zwracam hasło użytkownika z bazy
     * @see UserInterface
     * @return string
     */
    public function getPassword(): string{
        return (string) $this->password;
    }

    /**
     * Ustawiam hasło użytkownika
     * @param string $password
     * @return self
     */
    public function setPassword(string $password): self{
        $this->password = $password;
        return $this;
    }

    /**
     * Sprawdzam czy dany użytkownik jest zweryfikowany
     * @return bool
     */
    public function isVerified(): bool{
        return $this->isVerified;
    }

    /**
     * Ustawiam weryfikacje danego użytkownika
     * @param bool $isVerified
     * @return self
     */
    public function setIsVerified(bool $isVerified): self{
        $this->isVerified = $isVerified;
        return $this;
    }

    /**
     * Sprawdzam czy konto nie jest wyłączone
     * @return  boolean
     */ 
    public function isDisabled(): ?bool{
        return $this->isDisabled;
    }

    /**
     * Ustawiam informacje czy konto jest wyłączone
     * @param boolean $isDisabled
     * @return  self
     */ 
    public function setIsDisabled(bool $isDisabled): self {
        $this->isDisabled = $isDisabled;
        return $this;
    }

    /**
     * Zwracam datę utworzenia rekordu
     * @return Datetime|null
     */
    public function getUserCreatedAt() : ?\Datetime {
        return $this->user_created_at;
    }

    /**
     * Ustawiam datę utworzenia rekordu
     * @param \Datetime $user_created_at
     * @return self
     */
    public function setUserCreatedAt(\Datetime $user_created_at) :self{
        $this->user_created_at = $user_created_at;
        return $this;
    }

    /**
     * Zwracam datę ostatniej aktualizacji
     * @return \Datetime|null
     */
    public function getUserUpdatedAt(): ?\Datetime {
        return $this->user_updated_at;
    }

    /**
     * Ustawiam datę aktualizacji rekordu
     * @param \Datetime $user_updated_at
     * @return  self
     */ 
    public function setUserUpdatedAt(\Datetime $user_updated_at) :self{
        $this->user_updated_at = $user_updated_at;
        return $this;
    }

    /**
     * Automatycznie ustawiam czasy dodania i aktualizacji rekordu
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
    */
    public function updatedTimestamps(): void {
        $this->setUserUpdatedAt(new \DateTime());    
        if ($this->getUserCreatedAt() === null) {
            $this->setUserCreatedAt(new \DateTime());
        }
    }// end updatedTimestamps

    /*************************************************
     * implements UserInterface
     *************************************************/

    /**
     * Wirtualny identyfikator użytkownika
     *
     * @see UserInterface
     * @return string
     */
    public function getUsername(): string{
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(){
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(){
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /*************************************************
     * implements UserInterface
     *************************************************/

}// end class
