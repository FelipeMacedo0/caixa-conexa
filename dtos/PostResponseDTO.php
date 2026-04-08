<?php

namespace app\dtos;

class PostResponseDTO
{
    /** @var int|null */
    public $id;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
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
            'id' => $this->id,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function toObject(): object
    {
        return json_decode(json_encode($this->toArray()), false);
    }
}
