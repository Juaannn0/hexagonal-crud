<?php

namespace Modules\User\Domain\Exceptions;

use Exception;

class UserAlreadyExistsException extends Exception
{
    public function __construct(string $message = 'User already exists', int $code = 409)
    {
        parent::__construct($message, $code);
    }

    public static function withEmail(string $email): self
    {
        return new self("User with email {$email} already exists");
    }
}