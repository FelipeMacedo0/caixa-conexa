<?php

namespace app\dtos;
use Yii;

/**
 * Class ErrorResponseDTO
 * Represents an error response from the Conexa API.
 */
class ErrorResponseDTO
{
    /** @var string Main error message */
    public $message;

    /** @var ErrorDetailDTO[] List of detailed errors (optional) */
    public $errors = [];

    /** @var int HTTP status code */
    public $statusCode;

    public function __construct(array $data = [], int $statusCode = 400)
    {
        $this->message = $data['message'] ?? 'An error occurred';
        $this->statusCode = $statusCode;
        
        $errorsData = $data['errors'] ?? [];
        foreach ($errorsData as $error) {
            $this->errors[] = new ErrorDetailDTO($error);
        }
    }

    public static function fromArray(array $data, int $statusCode = 400): self
    {
        return new self($data, $statusCode);
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'statusCode' => $this->statusCode,
            'errors' => array_map(fn($error) => $error->toArray(), $this->errors),
        ];
    }

    public function toObject(): object
    {
        return json_decode(json_encode($this->toArray()), false);
    }

    /**
     * Gets all error messages as a flat array.
     * @return string[]
     */
    public function getAllMessages(): array
    {
        $messages = [$this->message];
        foreach ($this->errors as $error) {
            if ($error->message) {
                $messages[] = $error->message;
            }
            if (!empty($error->messages)) {
                foreach ($error->messages as $msg) {
                    $messages[] = $msg;
                }
            }
        }
        return array_unique($messages);
    }

    /**
     * Flashes all error messages to the session.
     */
    public function flash(): void
    {
        foreach ($this->getAllMessages() as $msg) {
            Yii::$app->session->addFlash('error', $msg);
        }
    }
}
