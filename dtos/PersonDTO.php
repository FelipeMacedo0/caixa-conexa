<?php

namespace app\dtos;

class PersonDTO
{
    /** @var int|null */
    public $personId;
    
    /** @var bool|null */
    public $isForeign;
    
    /** @var string|null */
    public $name;
    
    /** @var string|null */
    public $rg;
    
    /** @var string|null */
    public $issuingAuthority;
    
    /** @var string|null */
    public $cpf;
    
    /** @var \DateTimeImmutable|null */
    public $birthDate;
    
    /** @var string|null */
    public $maritalStatus;
    
    /** @var string|null */
    public $sex;
    
    /** @var string|null */
    public $nationality;
    
    /** @var string|null */
    public $placeOfBirth;
    
    /** @var string|null */
    public $notes;
    
    /** @var bool|null */
    public $isCompanyPartner;
    
    /** @var bool|null */
    public $isGuarantor;
    
    /** @var string|null */
    public $profession;
    
    /** @var string|null */
    public $cellNumber;
    
    /** @var string|null */
    public $jobTitle;
    
    /** @var string|null */
    public $photo;
    
    /** @var string|null */
    public $resume;
    
    /** @var bool|null */
    public $isIndividualCustomer;
    
    /** @var bool|null */
    public $hasLoginAccess;
    
    /** @var bool|null */
    public $isActive;
    
    /** @var int|null */
    public $customerId;
    
    /** @var int|null */
    public $companyId;
    
    /** @var \DateTimeImmutable|null */
    public $createdAt;
    
    /** @var \DateTimeImmutable|null */
    public $updatedAt;

    /**
     * Constructor
     * 
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->personId = $data['personId'] ?? null;
        $this->isForeign = $data['isForeign'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->rg = $data['rg'] ?? null;
        $this->issuingAuthority = $data['issuingAuthority'] ?? null;
        $this->cpf = $data['cpf'] ?? null;
        $this->birthDate = isset($data['birthDate']) 
            ? new \DateTimeImmutable($data['birthDate']) 
            : null;
        $this->maritalStatus = $data['maritalStatus'] ?? null;
        $this->sex = $data['sex'] ?? null;
        $this->nationality = $data['nationality'] ?? null;
        $this->placeOfBirth = $data['placeOfBirth'] ?? null;
        $this->notes = $data['notes'] ?? null;
        $this->isCompanyPartner = $data['isCompanyPartner'] ?? null;
        $this->isGuarantor = $data['isGuarantor'] ?? null;
        $this->profession = $data['profession'] ?? null;
        $this->cellNumber = $data['cellNumber'] ?? null;
        $this->jobTitle = $data['jobTitle'] ?? null;
        $this->photo = $data['photo'] ?? null;
        $this->resume = $data['resume'] ?? null;
        $this->isIndividualCustomer = $data['isIndividualCustomer'] ?? null;
        $this->hasLoginAccess = $data['hasLoginAccess'] ?? null;
        $this->isActive = $data['isActive'] ?? null;
        $this->customerId = $data['customerId'] ?? null;
        $this->companyId = $data['companyId'] ?? null;
        $this->createdAt = isset($data['createdAt']) 
            ? new \DateTimeImmutable($data['createdAt']) 
            : null;
        $this->updatedAt = isset($data['updatedAt']) 
            ? new \DateTimeImmutable($data['updatedAt']) 
            : null;
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
            'personId' => $this->personId,
            'isForeign' => $this->isForeign,
            'name' => $this->name,
            'rg' => $this->rg,
            'issuingAuthority' => $this->issuingAuthority,
            'cpf' => $this->cpf,
            'birthDate' => $this->birthDate ? $this->birthDate->format('Y-m-d') : null,
            'maritalStatus' => $this->maritalStatus,
            'sex' => $this->sex,
            'nationality' => $this->nationality,
            'placeOfBirth' => $this->placeOfBirth,
            'notes' => $this->notes,
            'isCompanyPartner' => $this->isCompanyPartner,
            'isGuarantor' => $this->isGuarantor,
            'profession' => $this->profession,
            'cellNumber' => $this->cellNumber,
            'jobTitle' => $this->jobTitle,
            'photo' => $this->photo,
            'resume' => $this->resume,
            'isIndividualCustomer' => $this->isIndividualCustomer,
            'hasLoginAccess' => $this->hasLoginAccess,
            'isActive' => $this->isActive,
            'customerId' => $this->customerId,
            'companyId' => $this->companyId,
            'createdAt' => $this->createdAt ? $this->createdAt->format(\DateTimeInterface::RFC3339) : null,
            'updatedAt' => $this->updatedAt ? $this->updatedAt->format(\DateTimeInterface::RFC3339) : null,
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
