<?php

namespace Modules\User\Domain\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    public function __construct(string $message = 'User not found', int $code = 404)
    {
        parent::__construct($message, $code);
    }

    public static function withId(int $id): self
    {
        return new self("User with ID {$id} not found");
    }

    public static function withEmail(string $email): self
    {
        return new self("User with email {$email} not found");
    }
}