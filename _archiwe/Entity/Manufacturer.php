<?php

namespace App\Entity;

use App\Entity\Product;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ManufacturerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * 
 * Klasa definiująca wszystkich dostawców produktów dla Daicon
 * 
 * @ORM\Entity(repositoryClass=ManufacturerRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Manufacturer{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id_manufacturer;

    /**
     * @ORM\Column(type="string", length=100)
     * @var string
     */
    private $manufacturer_name;

    /**
     * @ORM\Column(type="datetime")
     * @var \Datetime
     */
    private $manufacturer_created_at;

    /**
     * @ORM\Column(type="datetime")
     * @var \Datetime
     */    
    private $manufacturer_updated_at;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="manufacturer")
     */
    private $products;

    public function __construct() {
        $this->products = new ArrayCollection();
    }

    /**
     * Id dostawcy
     * @return int
     */
    public function getIdManufacturer(): ?int{
        return $this->id_manufacturer;
    }

    /**
     * Nazwa dostawcy produktu
     * @return string
     */ 
    public function getManufacturerName(): ?string{
        return $this->manufacturer_name;
    }

    /**
     * Wstawiam nazwę dostawcy
     * @param string $manufacturerName
     * @return  self
     */ 
    public function setManufacturerName($manufacturerName): self{
        $this->manufacturer_name = $manufacturerName;
        return $this;
    }

    /**
     * Zwracam datę utworzenia rekordu w bazie danych
     * @return  null|\Datetime
     */ 
    public function getManufacturerCreatedAt(): ?\Datetime {
        return $this->manufacturer_created_at;
    }

    /**
     * Ustawiam datę utworzenia rekordu
     * 
     * @param  \Datetime  $manufacturer_created_at
     * @return  self
     */ 
    public function setManufacturerCreatedAt(\Datetime $manufacturerCreatedAt): self{
        $this->manufacturer_created_at = $manufacturerCreatedAt;
        return $this;
    }

    /**
     * Zwracam datę ostatniej aktualizacji rekordu
     * @return null|\Datetime
     */ 
    public function getManufacturerUpdatedAt(): ?\Datetime{
        return $this->manufacturer_updated_at;
    }

    /**
     * Ustawiam datę ostatniej aktualizacji rekordu
     * 
     * @param \Datetime $manufacturerUpdatedAt
     * @return  self
     */ 
    public function setManufacturerUpdatedAt(\Datetime $manufacturerUpdatedAt): self{
        $this->manufacturer_updated_at = $manufacturerUpdatedAt;
        return $this;
    }

    /**
     * Automatycznie ustawiam czasy dodania i aktualizacji rekordu
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
    */
    public function updatedTimestamps(): void {
        $this->setManufacturerUpdatedAt(new \DateTime());    
        if ($this->getManufacturerCreatedAt() === null) {
            $this->setManufacturerCreatedAt(new \DateTime());
        }
    }// end updatedTimestamps

    /**
     * Pobieram listę produktów
     * @return Collection|Product[]
     */
    public function getProducts(): Collection {
        return $this->products;
    }

    /**
     * Dodaje produkt do kolekcji
     * @param Product $product
     * @return self
     */
    public function addProduct(Product $product): self {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setManufacturer($this);
        }
        return $this;
    }// end addProduct

    /**
     * Usuwam produkt z kolekcji
     * @param Product $product
     * @return self
     */
    public function removeProduct(Product $product): self{
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getManufacturer() === $this) {
                $product->setManufacturer(null);
            }
        }

        return $this;
    } // end remove Product   

}// end class
