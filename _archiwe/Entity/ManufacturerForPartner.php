<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Partner;
use App\Entity\Manufacturer;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ManufacturerForPartnerRepository;

/**
 * Klasa definiująca przypisanych dostawców dla danego partnera
 * 
 * @ORM\Entity(repositoryClass=ManufacturerForPartnerRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class ManufacturerForPartner
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id_manufacturer_for_partner;

    /**
     * @ORM\ManyToOne(targetEntity=Manufacturer::class)
     * @ORM\JoinColumn(name="id_manufacturer", referencedColumnName="id_manufacturer", nullable=false)
     * @var Manufacturer
     */
    private $manufacturer;

    /**
     * @ORM\ManyToOne(targetEntity=Partner::class)
     * @ORM\JoinColumn(name="id_partner", referencedColumnName="id_partner", nullable=false)
     * @var Partner
     */
    private $partner;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @var float
     */
    private $partner_special_profit;

    /**
     * @ORM\Column(type="datetime")
     * @var \Datetime
     */    
    private $mfp_created_at;

    /**
     * @ORM\Column(type="datetime")
     * @var \Datetime
     */    
    private $mfp_updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id", nullable=false)
     * @var User
     */    
    private $user_added;
    
    /**
     * ID ManufacturerForPartner
     * @return int|null
     */
    public function getIdManufacturerForPartner(): ?int {
        return $this->id_manufacturer_for_partner;
    }

    /**
     * Dostawca
     * @return Manufacturer|null
     */
    public function getManufacturer(): ?Manufacturer {
        return $this->manufacturer;
    }

    /**
     * Ustawiam dostawcę
     * @param Manufacturer|null $manufacturer
     * @return self
     */
    public function setManufacturer(?Manufacturer $manufacturer): self {
        $this->manufacturer = $manufacturer;
        return $this;
    }

    /**
     * Partner
     * @return Partner|null
     */
    public function getPartner(): ?Partner {
        return $this->partner;
    }

    /**
     * Ustawiam partnera
     * @param Partner|null $partner
     * @return self
     */
    public function setPartner(?Partner $partner): self {
        $this->partner = $partner;
        return $this;
    }

    /**
     * Zwracam specjalny narzut dla partnera
     * @return float|null
     */
    public function getPartnerSpecialProfit(): ?float {
        return $this->partner_special_profit;
    }

    /**
     * Ustawiam specjalny narzut dla partnera
     * @param float|null $partner_special_profit
     * @return self
     */
    public function setPartnerSpecialProfit(?float $partner_special_profit): self {
        $this->partner_special_profit = $partner_special_profit;
        return $this;
    }

    /**
     * Data utworzenia rekordu
     * @return \Datetime|null
     */
    public function getMfpCreatedAt(): ?\Datetime {
        return $this->mfp_created_at;
    }

    /**
     * Ustawiam datę utworzenia rekordu
     * @param \Datetime $mfp_created_at
     * @return  self
     */ 
    public function setMfpCreatedAt(\Datetime $mfp_created_at): self {
        $this->mfp_created_at = $mfp_created_at;
        return $this;
    }
    
    /**
     * Data aktualizacji rekordu
     * @return \Datetime|null
     */
    public function getMfpUpdatedAt(): ?\Datetime {
        return $this->mfp_updated_at;
    }

    /**
     * Ustawiam datę ostatniej aktualizacji rekordu
     * @param \Datetime $mfp_updated_at
     * @return  self
     */ 
    public function setMfpUpdatedAt(\Datetime $mfp_updated_at): self {
        $this->mfp_updated_at = $mfp_updated_at;
        return $this;
    }

    /**
     * Zwracam użytkownika dodającego dany zapis
     * @return User
     */ 
    public function getUserAdded(): ?User {
        return $this->user_added;
    }

    /**
     * Ustawiam użytkownika dodającego dany rekord
     * @param User $user_added
     * @return  self
     */ 
    public function setUserAdded(User $user_added){
        $this->user_added = $user_added;
        return $this;
    }

    /**
     * Automatycznie ustawiam czasy dodania i aktualizacji rekordu
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
    */
    public function updatedTimestamps(): void {
        $this->setMfpUpdatedAt(new \DateTime());    
        if ($this->getMfpCreatedAt() === null) {
            $this->setMfpCreatedAt(new \DateTime());
        }
    }// end updatedTimestamps

}// end class
