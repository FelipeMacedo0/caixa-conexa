<?php

namespace app\dtos;

class PaginationDTO
{
    /** @var int|null */
    private $limit;
    
    /** @var int|null */
    private $offset;
    
    /** @var bool|null */
    private $hasNext;

    public function __construct(array $data = [])
    {
        $this->limit = $data['limit'] ?? null;
        $this->offset = $data['offset'] ?? null;
        $this->hasNext = $data['hasNext'] ?? null;
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true);
        return new self($data ?: []);
    }

    public function toArray(): array
    {
        return [
            'limit' => $this->limit,
            'offset' => $this->offset,
            'hasNext' => $this->hasNext,
        ];
    }

    public function toJson(int $options = JSON_PRETTY_PRINT): string
    {
        return json_encode($this->toArray(), $options);
    }

    public function toObject(): object
    {
        return json_decode(json_encode($this->toArray()), false);
    }

    // Getters
    public function getLimit(): ?int { return $this->limit; }
    public function getOffset(): ?int { return $this->offset; }
    public function getHasNext(): ?bool { return $this->hasNext; }

    // Setters (fluent)
    public function setLimit(?int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function setOffset(?int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function setHasNext(?bool $hasNext): self
    {
        $this->hasNext = $hasNext;
        return $this;
    }
}