<?php

namespace App\Entity;

use App\Repository\PartnerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Klasa definiująca wszystkich partnerów handlowych
 * 
 * @ORM\Entity(repositoryClass=PartnerRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Partner
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */    
    private $id_partner;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $partner_name;


    /**
     * @ORM\Column(type="float", nullable=false, options={"default" : 1})
     * @var float
     */
    private $partner_default_margin = 1;

    /**
     * @ORM\Column(type="datetime")
     * @var \Datetime
     */    
    private $partner_created_at;

    /**
     * @ORM\Column(type="datetime")
     * @var \Datetime
     */    
    private $partner_updated_at;

    /**
     * ID partnera
     * @return int|null
     */
    public function getIdPartner(): ?int {
        return $this->id_partner;
    }

    /**
     * Ustawiam id partnera
     * @param int $id_partner
     * @return self
     */
    public function setIdPartner(int $id_partner): self{
        $this->id_partner = $id_partner;
        return $this;
    }

    /**
     * Zwracam nazwę partnera
     * @return string|null
     */
    public function getPartnerName(): ?string{
        return $this->partner_name;
    }

    /**
     * Ustawiam nazwę partnera
     * @param string $partner_name
     * @return self
     */
    public function setPartnerName(string $partner_name): self{
        $this->partner_name = $partner_name;
        return $this;
    }

    /**
     * Zwracam domyślny narzut dla danego partnera
     * @return  float|null
     */ 
    public function getPartnerDefaultMargin(): ?float {
        return $this->partner_default_margin;
    }

    /**
     * Ustawiam domyślny narzut dla danego
     * @param  float  $partner_default_margin
     * @return  self
     */ 
    public function setPartnerDefaultMargin(float $partner_default_margin): self {
        $this->partner_default_margin = $partner_default_margin;
        return $this;
    }

    /**
     * Zwracam datę utworzenia rekordu
     * @return  null|\Datetime
     */ 
    public function getPartnerCreatedAt(): ?\Datetime {
        return $this->partner_created_at;
    }

    /**
     * Ustawiam datę utworzenia rekordu
     * @param  \Datetime  $partner_created_at
     * @return  self
     */ 
    public function setPartnerCreatedAt(\Datetime $partner_created_at){
        $this->partner_created_at = $partner_created_at;
        return $this;
    }    

    /**
     * Zwracam datę ostatniej aktualizacji rekordu
     * @return  \Datetime
     */ 
    public function getPartnerUpdatedAt() : ?\Datetime {
        return $this->partner_updated_at;
    }

    /**
     * Ustawiam datę ostatniej aktualizacji rekordu
     * @param  \Datetime  $partner_updated_at
     * @return  self
     */ 
    public function setPartnerUpdatedAt(\Datetime $partner_updated_at){
        $this->partner_updated_at = $partner_updated_at;
        return $this;
    }

    /**
     * Automatycznie ustawiam czasy dodania i aktualizacji rekordu
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
    */
    public function updatedTimestamps(): void {
        $this->setPartnerUpdatedAt(new \DateTime());    
        if ($this->getPartnerCreatedAt() === null) {
            $this->setPartnerCreatedAt(new \DateTime());
        }
    }// end updatedTimestamps

}// end class
