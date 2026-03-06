<?php

namespace App\Models;

class Product
{
    private string $id;
    private string $name;
    private string $category;
    private string $subcategory;
    private string $brand;
    private string $mrp;
    private string $image;
    private string $description;
    private array $specs;
    private ?string $brochure = null;

    public function __construct(array $data)
    {
        $this->setId($data['id']);
        $this->setName($data['name']);
        $this->setCategory($data['category']);
        $this->setSubcategory($data['subcategory']);
        $this->setBrand($data['brand']);
        $this->setMrp($data['mrp']);
        $this->setImage($data['image']);
        $this->setDescription($data['description']);
        $this->setSpecs($data['specs'] ?? []);
        $this->setBrochure($data['brochure'] ?? null);
    }

    // Getters
    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getCategory(): string { return $this->category; }
    public function getSubcategory(): string { return $this->subcategory; }
    public function getBrand(): string { return $this->brand; }
    public function getMrp(): string { return $this->mrp; }
    public function getImage(): string { return $this->image; }
    public function getDescription(): string { return $this->description; }
    public function getSpecs(): array { return $this->specs; }
    public function getBrochure(): ?string { return $this->brochure; }

    // Setters
    public function setId(string $id): void { $this->id = $id; }
    public function setName(string $name): void 
    { 
        $this->name = trim(ucwords(strtolower($name))); 
    }
    public function setCategory(string $category): void 
    { 
        $this->category = trim($category); 
    }
    public function setSubcategory(string $subcategory): void 
    { 
        $this->subcategory = trim($subcategory); 
    }
    public function setBrand(string $brand): void 
    { 
        $this->brand = trim($brand); 
    }
    public function setMrp(string $mrp): void 
    { 
        // Clean MRP: "₹2,50,000" → "250000"
        $this->mrp = preg_replace('/[^0-9]/', '', $mrp); 
    }
    public function setImage(string $image): void 
    { 
        $this->image = trim($image); 
    }
    public function setDescription(string $description): void 
    { 
        $this->description = trim($description); 
    }
    public function setSpecs(array $specs): void 
    { 
        $this->specs = $specs; 
    }
    public function setBrochure(?string $brochure): void 
    { 
        $this->brochure = $brochure ? trim($brochure) : null; 
    }

    // Computed getters (like C# properties)
    public function getMrpFormatted(): string
    {
        $numeric = (int) $this->mrp;
        return '₹' . number_format($numeric, 0, ',', ',');
    }

    public function hasBrochure(): bool
    {
        return !empty($this->brochure);
    }

    // JSON serialization
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'subcategory' => $this->subcategory,
            'brand' => $this->brand,
            'mrp' => $this->mrp,
            'mrp_formatted' => $this->getMrpFormatted(),
            'image' => $this->image,
            'description' => $this->description,
            'specs' => $this->specs,
            'brochure' => $this->brochure,
            'has_brochure' => $this->hasBrochure(),
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}
