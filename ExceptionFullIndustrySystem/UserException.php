<?php

declare(strict_types=1);

namespace App\Exceptions;

class UserException extends AppException
{
    public static function notFound(int $id): static
    {
        return new static("User #$id not found", 404);
    }

    public static function emailTaken(string $email): static
    {
        return new static("Email '$email' already in use", 409);
    }
}
