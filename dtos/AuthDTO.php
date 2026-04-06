<?php
namespace app\dtos;

class AuthDTO
{
    public UserDTO $user;
    public string $tokenType;
    public string $accessToken;
    public int $expiresIn;
    public int $statusCode;

    public function __construct(array $data)
    {
        $this->user = new UserDTO($data['user'] ?? []);
        $this->tokenType = $data['tokenType'] ?? '';
        $this->accessToken = $data['accessToken'] ?? '';
        $this->expiresIn = $data['expiresIn'] ?? 0;
        $this->statusCode = $data['statusCode'] ?? 401;
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function toArray(): array
    {
        return [
            'user' => $this->user->toArray(),
            'tokenType' => $this->tokenType,
            'accessToken' => $this->accessToken,
            'expiresIn' => $this->expiresIn
        ];
    }

    public function toObject(): object
    {
        return json_decode(json_encode($this->toArray()), false);
    }
}
