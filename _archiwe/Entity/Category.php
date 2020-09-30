<?php

namespace App\Entity;

use App\Entity\Product;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Klasa definiująca kategorie produktów 
 * 
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Category
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id_category;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $category_name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $category_disabled;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $category_order_by;

    /**
     * @ORM\Column(type="datetime")
     */
    private $category_created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $category_updated_at;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="category")
     */
    private $products;

    public function __construct(){
        $this->products = new ArrayCollection();
    }


    /**
     * ID kategorii
     * @return int|null
     */
    public function getIdCategory(): ?int {
        return $this->id_category;
    }

    /**
     * Nazwa danej kategorii
     * @return string|null
     */
    public function getCategoryName(): ?string {
        return $this->category_name;
    }

    /**
     * Ustawiam nazwę kategorii
     * @param string $category_name
     * @return self
     */
    public function setCategoryName(string $category_name): self {
        $this->category_name = $category_name;
        return $this;
    }

    /**
     * Sprawdzam czy dana kategoria jest włączona
     * @return bool|null
     */
    public function getCategoryDisabled(): ?bool {
        return $this->category_disabled;
    }

    /**
     * Ustawiam czy dana kategoria jest włączona
     * @param bool $category_disabled
     * @return self
     */
    public function setCategoryDisabled(bool $category_disabled): self {
        $this->category_disabled = $category_disabled;
        return $this;
    }

    /**
     * Sprawdzam kolejność kategorii
     * @return int|null
     */
    public function getCategoryOrderBy(): ?int{
        return $this->category_order_by;
    }

    /**
     * ustawiam kolejność kategorii
     * @param int $category_order_by
     * @return self
     */
    public function setCategoryOrderBy(int $category_order_by): self {
        $this->category_order_by = $category_order_by;
        return $this;
    }

    /**
     * Pobieram datę utworzenia rekordu
     * @return \DateTimeInterface|null
     */
    public function getCategoryCreatedAt(): ?\DateTime {
        return $this->category_created_at;
    }

    /**
     * Ustawiam datę utworzenia rekordu
     * @param \DateTime $category_created_at
     * @return self
     */
    public function setCategoryCreatedAt(\DateTime $category_created_at): self {
        $this->category_created_at = $category_created_at;
        return $this;
    }

    /**
     * Pobieram datę ostatniej aktualizacji rekordu
     * @return \DateTime|null
     */
    public function getCategoryUpdatedAt(): ?\DateTime{
        return $this->category_updated_at;
    }

    /**
     * Ustawiam datę ostatniej aktualizacji rekordu
     * @param \DateTime $category_updated_at
     * @return self
     */
    public function setCategoryUpdatedAt(\DateTime $category_updated_at): self{
        $this->category_updated_at = $category_updated_at;
        return $this;
    }

    /**
     * automatycznie ustawiam czasy dodania i aktualizacji rekordu
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @return void
    */
    public function updatedTimestamps(): void {
        $this->setCategoryUpdatedAt(new \DateTime());    
        if ($this->getCategoryCreatedAt() === null) {
            $this->setCategoryCreatedAt(new \DateTime());
        }
    } // end updatedTimestamps   

    /**
     * Pobieram listę produktów 
     * @return Collection|Product[]
     */
    public function getProducts(): Collection{
        return $this->products;
    }

    /**
     * Dodaje produkt do kolekcji 
     * @param Product $product
     * @return self
     */
    public function addProduct(Product $product): self{
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCategory($this);
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
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }
        return $this;
    }// end removeProduct

}// end class
