<?php

declare(strict_types=1);

namespace App\API;

class BaseAPIResponse
{
    private bool $success = true;

    private ?string $error = null;

    private $data;

    private function __construct()
    {
    }

    public static function createSuccessResponse(): self
    {
        return new self();
    }

    public static function createSuccessWithData($data): self
    {
        $response = new self();
        $response->data = $data;

        return $response;
    }

    public static function createErrorResponse(string $errorMessage): self
    {
        $response = new self();
        $response->success = false;
        $response->error = $errorMessage;

        return $response;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
