<?php

namespace App\Entity;

use App\Entity\Category;
use App\Entity\Manufacturer;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Klasa definiująca produkty wyświetlane na stronie głównej
 * 
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @UniqueEntity(fields={"product_manufacturer_symbol"}, message="Podany symbol już występuje w bazie danych.")
 * @ORM\HasLifecycleCallbacks
 */
class Product {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id_product", type="integer")
     * @var int
     */
    private $id_product;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @ORM\JoinColumn(name="id_category", referencedColumnName="id_category", nullable=false)
     * @var App\Entity\Category
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Manufacturer::class, inversedBy="products")
     * @ORM\JoinColumn(name="id_manufacturer", referencedColumnName="id_manufacturer", nullable=false)
     * @var App\Entity\Manufacturer
     */
    private $manufacturer;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string 
     */
    private $product_name;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    private $product_description;

    /**
     * @ORM\Column(type="string", length=45, unique=true)
     * @var string
     */
    private $product_manufacturer_symbol;

    /**
     * @ORM\Column(type="float")
     * @var float
     */
    private $product_manufacturer_price;

    /**
     * 
     * @ORM\Column(type="json", nullable=true)
     * @Assert\Json(message = "This is not valid JSON")
     * @Assert\Type("array")
     * @var array
     */
    private $product_details = [];

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $product_disabled;

    /**
     * @ORM\Column(type="datetime")
     * @var \Datetime
     */
    private $product_created_at;
    
    /**
     * @ORM\Column(type="datetime")
     * @var \Datetime
     */
    private $product_updated_at;


    /**
     * ID produktu
     * @return int|null
     */
    public function getIdProduct(): ?int {
        return $this->id_product;
    }

    /**
     * ID kategorii
     * @return Category|null
     */
    public function getCategory(): ?Category {
        return $this->category;
    }

    /**
     * Ustawiam kategorię
     * @param Category|null $category
     * @return self
     */
    public function setCategory(?Category $category): self {
        $this->category = $category;
        return $this;
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
     * Zwracam nazwę produktu
     * @return string|null
     */
    public function getProductName(): ?string {
        return $this->product_name;
    }

    /**
     * Ustawiam nazwę produktu
     * @param string $product_name
     * @return self
     */
    public function setProductName(string $product_name): self {
        $this->product_name = $product_name;
        return $this;
    }

    /**
     * Zwracam opis produktu
     * @return string|null
     */
    public function getProductDescription(): ?string {
        return $this->product_description;
    }

    /**
     * Ustawiam opis produktu
     * @param string $product_description
     * @return self
     */
    public function setProductDescription(string $product_description): self {
        $this->product_description = $product_description;
        return $this;
    }

    /**
     * Zwracam kod producenta
     * @return string|null
     */
    public function getProductManufacturerSymbol(): ?string {
        return $this->product_manufacturer_symbol;
    }

    /**
     * Ustawiam kod producenta
     * @param string $product_manufacturer_symbol
     * @return self
     */
    public function setProductManufacturerSymbol(string $product_manufacturer_symbol): self{
        $this->product_manufacturer_symbol = $product_manufacturer_symbol;
        return $this;
    }

    /**
     * Zwracam cenę producenta
     * @return float|null
     */
    public function getProductManufacturerPrice(): ?float {
        return $this->product_manufacturer_price;
    }

    /**
     * Ustawiam cenę producenta
     * @param float $product_manufacturer_price
     * @return self
     */
    public function setProductManufacturerPrice(float $product_manufacturer_price): self {
        $this->product_manufacturer_price = $product_manufacturer_price;
        return $this;
    }

    /**
     * Tablica szczegółów produktu
     * @return array
     */
    public function getProductDetails(): array {
        if($this->product_details === null){
            return [];
        }
        return $this->product_details;
    }

    /**
     * Ustawiam tablicę szczegółów produktu
     * @param array|null $product_details
     * @return self
     */
    public function setProductDetails(array $product_details): self {
        $this->product_details = $product_details;
        return $this;
    }

    /**
     * Zwracam czy produkt jest wyłączony
     * @return bool|null
     */
    public function getProductDisabled(): ?bool {
        return $this->product_disabled;
    }


    /**
     * Ustawiam czy produkt jest wyłączony
     * @param bool $product_disabled
     * @return self
     */
    public function setProductDisabled(bool $product_disabled): self {
        $this->product_disabled = $product_disabled;
        return $this;
    }

    /**
     * Zwracam datę utworzenia rekordu
     * @return  null|\Datetime
     */ 
    public function getProductCreatedAt(): ?\Datetime {
        return $this->product_created_at;
    }

    /**
     * Ustawiam datę utworzenia rekordu
     * @param  \Datetime  $product_created_at
     * @return  self
     */ 
    public function setProductCreatedAt(\Datetime $product_created_at){
        $this->product_created_at = $product_created_at;
        return $this;
    }

    /**
     * Zwracam datę ostatniej aktualizacji rekordu
     * @return  \Datetime
     */ 
    public function getProductUpdatedAt() : ?\Datetime {
        return $this->product_updated_at;
    }

    /**
     * Ustawiam datę ostatniej aktualizacji rekordu
     * @param  \Datetime  $product_updated_at
     * @return  self
     */ 
    public function setProductUpdatedAt(\Datetime $product_updated_at){
        $this->product_updated_at = $product_updated_at;
        return $this;
    }

    /**
     * Automatycznie ustawiam czasy dodania i aktualizacji rekordu
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
    */
    public function updatedTimestamps(): void {
        $this->setProductUpdatedAt(new \DateTime());    
        if ($this->getProductCreatedAt() === null) {
            $this->setProductCreatedAt(new \DateTime());
        }
    }// end updatedTimestamps

}// end class
