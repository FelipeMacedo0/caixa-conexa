<?php

namespace app\dtos;

/**
 * Class ErrorDetailDTO
 * Repreents an individual error entry within the ErrorResponseDTO.
 * Handles both validation errors (400) and business rule errors (422).
 */
class ErrorDetailDTO
{
    /** @var string|null Field name (Validation Error - HTTP 400) */
    public $field;

    /** @var array|null List of messages for the field (Validation Error - HTTP 400) */
    public $messages;

    /** @var string|null Error code (Processing Error - HTTP 422) */
    public $code;

    /** @var string|null Error message (Processing Error - HTTP 422) */
    public $message;

    public function __construct(array $data = [])
    {
        $this->field = $data['field'] ?? null;
        $this->messages = $data['messages'] ?? null;
        $this->code = $data['code'] ?? null;
        $this->message = $data['message'] ?? null;
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function toArray(): array
    {
        $result = [];
        if ($this->field !== null) $result['field'] = $this->field;
        if ($this->messages !== null) $result['messages'] = $this->messages;
        if ($this->code !== null) $result['code'] = $this->code;
        if ($this->message !== null) $result['message'] = $this->message;
        
        return $result;
    }

    public function toObject(): object
    {
        return (object) $this->toArray();
    }
}
