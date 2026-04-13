<?php

namespace app\dtos;

class PersonsDTO
{
    /** @var PersonDTO[] */
    private $data;
    
    /** @var PaginationDTO|null */
    private $pagination;

    public function __construct(array $data = [], PaginationDTO $pagination = null)
    {
        $this->data = [];
        $this->pagination = $pagination;

        foreach ($data as $item) {
            if ($item instanceof PersonDTO) {
                $this->data[] = $item;
            } elseif (is_array($item)) {
                $this->data[] = PersonDTO::fromArray($item);
            }
        }
    }

    /**
     * Cria um DTO a partir de um array da API
     */
    public static function fromArray(array $response): self
    {
        $data = [];
        $pagination = null;

        if (isset($response['data']) && is_array($response['data'])) {
            $data = $response['data'];
        }

        if (isset($response['pagination']) && is_array($response['pagination'])) {
            $pagination = PaginationDTO::fromArray($response['pagination']);
        }

        return new self($data, $pagination);
    }

    /**
     * Cria um DTO a partir de um JSON da API
     */
    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true);
        return self::fromArray($data ?: []);
    }

    /**
     * Converte o DTO para array
     */
    public function toArray(): array
    {
        $dataArray = array_map(function ($person) {
            return $person instanceof PersonDTO ? $person->toArray() : $person;
        }, $this->data);

        return [
            'data' => $dataArray,
            'pagination' => $this->pagination ? $this->pagination->toArray() : null,
        ];
    }

    /**
     * Converte o DTO para JSON
     */
    public function toJson(int $options = JSON_PRETTY_PRINT): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Converte o DTO para object
     */
    public function toObject(): object
    {
        return json_decode(json_encode($this->toArray()), false);
    }

    // Getters
    public function getData(): array { return $this->data; }
    public function getPagination(): ?PaginationDTO { return $this->pagination; }

    // Setters
    public function setData(array $data): self
    {
        $this->data = [];
        foreach ($data as $item) {
            if ($item instanceof PersonDTO) {
                $this->data[] = $item;
            } elseif (is_array($item)) {
                $this->data[] = PersonDTO::fromArray($item);
            }
        }
        return $this;
    }

    public function setPagination(?PaginationDTO $pagination): self
    {
        $this->pagination = $pagination;
        return $this;
    }

    /**
     * Retorna a primeira pessoa da lista
     */
    public function getFirst(): ?PersonDTO
    {
        return $this->data[0] ?? null;
    }

    /**
     * Verifica se a lista está vazia
     */
    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    /**
     * Retorna a quantidade de itens na lista atual
     */
    public function count(): int
    {
        return count($this->data);
    }
}
