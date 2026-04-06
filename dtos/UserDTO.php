<?php
namespace app\dtos;

class UserDTO
{
    public int $id;
    public string $type;
    public string $name;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->type = $data['type'] ?? '';
        $this->name = $data['name'] ?? '';
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name
        ];
    }

    public function toObject(): object
    {
        return json_decode(json_encode($this->toArray()), false);
    }
}