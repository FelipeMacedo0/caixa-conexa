<?php

namespace app\dtos;

class ProductDTO
{    /** @var int|null */
    public $productId;
    
    /** @var string|null */
    public $name;
    
    /** @var string|null */
    public $description;
    
    /** @var float|null */
    public $price;
    
    /** @var bool|null */
    public $active;
    
    /** @var bool|null */
    public $isCustomerConsumable;
    
    /** @var \DateTimeImmutable|null */
    public $createdAt;
    
    /** @var \DateTimeImmutable|null */
    public $updatedAt;
        /** @var int|null */
    public $categoryId;
    
    /** @var int|null */
    public $companyId;
    
    /** @var int|null */
    public $costCenterId;
    
    /** @var array */
    public $notificationsEmails;
    
    /** @var string|null */
    public $nfseDescription;

    /**
     * Constructor
     * 
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->productId = $data['productId'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->price = isset($data['price']) ? (float) $data['price'] : null;
        $this->active = $data['active'] ?? null;
        $this->isCustomerConsumable = $data['isCustomerConsumable'] ?? null;
        $this->createdAt = isset($data['createdAt']) 
            ? new \DateTimeImmutable($data['createdAt']) 
            : null;
        $this->updatedAt = isset($data['updatedAt']) 
            ? new \DateTimeImmutable($data['updatedAt']) 
            : null;
        $this->categoryId = $data['categoryId'] ?? null;
        $this->companyId = $data['companyId'] ?? null;
        $this->costCenterId = $data['costCenterId'] ?? null;
        $this->notificationsEmails = $data['notificationsEmails'] ?? [];
        $this->nfseDescription = $data['nfseDescription'] ?? null;
    }

    /**
     * Creates a DTO from an array
     * 
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    /**
     * Creates a DTO from JSON string
     * 
     * @param string $json
     * @return self
     */
    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true);
        return new self($data ?: []);
    }

    /**
     * Converts DTO to array
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'productId' => $this->productId,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'active' => $this->active,
            'isCustomerConsumable' => $this->isCustomerConsumable,
            'createdAt' => $this->createdAt ? $this->createdAt->format(\DateTimeInterface::RFC3339) : null,
            'updatedAt' => $this->updatedAt ? $this->updatedAt->format(\DateTimeInterface::RFC3339) : null,
            'categoryId' => $this->categoryId,
            'companyId' => $this->companyId,
            'costCenterId' => $this->costCenterId,
            'notificationsEmails' => $this->notificationsEmails,
            'nfseDescription' => $this->nfseDescription,
        ];
    }

    /**
     * Converts DTO to JSON string
     * 
     * @param int $options
     * @return string
     */
    public function toJson(int $options = JSON_PRETTY_PRINT): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Converts DTO to object
     * 
     * @return object
     */
    public function toObject(): object
    {
        return json_decode(json_encode($this->toArray()), false);
    }
}