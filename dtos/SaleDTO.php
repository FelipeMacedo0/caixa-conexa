<?php

namespace app\dtos;

class SaleDTO
{
    /** @var string|null */
    public $status;
    
    /** @var float|null */
    public $discountValue;
    
    /** @var \DateTimeImmutable|null */
    public $createdAt;
    
    /** @var \DateTimeImmutable|null */
    public $updatedAt;
    
    /** @var ProductDTO|null */
    public $product;
    
    /** @var int|null */
    public $contractId;
    
    /** @var int|null */
    public $recurringSaleId;
    
    /** @var int|null */
    public $saleId;
    
    /** @var float|null */
    public $amount;
    
    /** @var int|null */
    public $sellerId;
    
    /** @var float|null */
    public $quantity;
    
    /** @var int|null */
    public $productId;
    
    /** @var int|null */
    public $customerId;
    
    /** @var int|null */
    public $requesterId;
    
    /** @var \DateTimeImmutable|null */
    public $referenceDate;
    
    /** @var float|null */
    public $originalAmount;
    
    /** @var string|null */
    public $notes;

    /**
     * Constructor
     * 
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->status = $data['status'] ?? null;
        $this->discountValue = isset($data['discountValue']) ? (float) $data['discountValue'] : null;
        
        $this->createdAt = isset($data['createdAt']) 
            ? new \DateTimeImmutable($data['createdAt']) 
            : null;
            
        $this->updatedAt = isset($data['updatedAt']) 
            ? new \DateTimeImmutable($data['updatedAt']) 
            : null;
            
        $this->product = isset($data['product']) && is_array($data['product']) 
            ? ProductDTO::fromArray($data['product']) 
            : null;
            
        $this->contractId = isset($data['contractId']) ? (int) $data['contractId'] : null;
        $this->recurringSaleId = isset($data['recurringSaleId']) ? (int) $data['recurringSaleId'] : null;
        $this->saleId = isset($data['saleId']) ? (int) $data['saleId'] : null;
        $this->amount = isset($data['amount']) ? (float) $data['amount'] : null;
        $this->sellerId = isset($data['sellerId']) ? (int) $data['sellerId'] : null;
        $this->quantity = isset($data['quantity']) ? (float) $data['quantity'] : null;
        $this->productId = isset($data['productId']) ? (int) $data['productId'] : null;
        $this->customerId = isset($data['customerId']) ? (int) $data['customerId'] : null;
        $this->requesterId = isset($data['requesterId']) ? (int) $data['requesterId'] : null;
        
        $this->referenceDate = isset($data['referenceDate']) 
            ? new \DateTimeImmutable($data['referenceDate']) 
            : null;
            
        $this->originalAmount = isset($data['originalAmount']) ? (float) $data['originalAmount'] : null;
        $this->notes = $data['notes'] ?? null;
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
            'status' => $this->status,
            'discountValue' => $this->discountValue,
            'createdAt' => $this->createdAt ? $this->createdAt->format(\DateTimeInterface::RFC3339) : null,
            'updatedAt' => $this->updatedAt ? $this->updatedAt->format(\DateTimeInterface::RFC3339) : null,
            'product' => $this->product ? $this->product->toArray() : null,
            'contractId' => $this->contractId,
            'recurringSaleId' => $this->recurringSaleId,
            'saleId' => $this->saleId,
            'amount' => $this->amount,
            'sellerId' => $this->sellerId,
            'quantity' => $this->quantity,
            'productId' => $this->productId,
            'customerId' => $this->customerId,
            'requesterId' => $this->requesterId,
            'referenceDate' => $this->referenceDate ? $this->referenceDate->format(\DateTimeInterface::RFC3339) : null,
            'originalAmount' => $this->originalAmount,
            'notes' => $this->notes,
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
