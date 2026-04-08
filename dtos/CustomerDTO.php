<?php

namespace app\dtos;

class CustomerDTO
{
    /** @var int|null */
    public $companyId;
    
    /** @var int|null */
    public $customerId;
    
    /** @var string|null */
    public $name;
    
    /** @var string|null */
    public $tradeName;
    
    /** @var string|null */
    public $pronunciation;
    
    /** @var string|null */
    public $fieldOfActivity;
    
    /** @var string|null */
    public $notes;
    
    /** @var string|null */
    public $cellNumber;
    
    /** @var string|null */
    public $website;
    
    /** @var bool|null */
    public $hasLoginAccess;
    
    /** @var string|null */
    public $login;
    
    /** @var string|null */
    public $password;
    
    /** @var string|null */
    public $automaticallyIssueNfse;
    
    /** @var string|null */
    public $notesNfse;
    
    /** @var array */
    public $taxDeductions;
    
    /** @var array */
    public $legalPerson;
    
    /** @var array */
    public $address;
    
    /** @var array */
    public $phones;
    
    /** @var array */
    public $emailsMessage;
    
    /** @var array */
    public $emailsFinancialMessages;

    /**
     * Constructor
     * 
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->companyId = $data['companyId'] ?? null;
        $this->customerId = $data['customerId'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->tradeName = $data['tradeName'] ?? null;
        $this->pronunciation = $data['pronunciation'] ?? null;
        $this->fieldOfActivity = $data['fieldOfActivity'] ?? null;
        $this->notes = $data['notes'] ?? null;
        $this->cellNumber = $data['cellNumber'] ?? null;
        $this->website = $data['website'] ?? null;
        $this->hasLoginAccess = $data['hasLoginAccess'] ?? null;
        $this->login = $data['login'] ?? null;
        $this->password = $data['password'] ?? null;
        $this->automaticallyIssueNfse = $data['automaticallyIssueNfse'] ?? null;
        $this->notesNfse = $data['notesNfse'] ?? null;
        $this->taxDeductions = $data['taxDeductions'] ?? [];
        $this->legalPerson = $data['legalPerson'] ?? [];
        $this->address = $data['address'] ?? [];
        $this->phones = $data['phones'] ?? [];
        $this->emailsMessage = $data['emailsMessage'] ?? [];
        $this->emailsFinancialMessages = $data['emailsFinancialMessages'] ?? [];
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
            'companyId' => $this->companyId,
            'customerId' => $this->customerId,
            'name' => $this->name,
            'tradeName' => $this->tradeName,
            'pronunciation' => $this->pronunciation,
            'fieldOfActivity' => $this->fieldOfActivity,
            'notes' => $this->notes,
            'cellNumber' => $this->cellNumber,
            'website' => $this->website,
            'hasLoginAccess' => $this->hasLoginAccess,
            'login' => $this->login,
            'password' => $this->password,
            'automaticallyIssueNfse' => $this->automaticallyIssueNfse,
            'notesNfse' => $this->notesNfse,
            'taxDeductions' => $this->taxDeductions,
            'legalPerson' => $this->legalPerson,
            'address' => $this->address,
            'phones' => $this->phones,
            'emailsMessage' => $this->emailsMessage,
            'emailsFinancialMessages' => $this->emailsFinancialMessages,
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
