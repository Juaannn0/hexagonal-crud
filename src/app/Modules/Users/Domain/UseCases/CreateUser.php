<?php

namespace Modules\User\Domain\UseCases;

use Modules\User\Domain\Entities\User;
use Modules\User\Domain\ValueObjects\Email;
use Modules\User\Domain\Ports\UserRepositoryInterface;
use Modules\User\Domain\Exceptions\UserAlreadyExistsException;

class CreateUser
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $name, string $email, string $password): User
    {
        // Validar que el email no exista
        $emailVO = new Email($email);
        
        if ($this->repository->emailExists($emailVO->getValue())) {
            throw UserAlreadyExistsException::withEmail($email);
        }

        // Crear la entidad User
        $user = new User(
            id: null,
            name: $name,
            email: $emailVO,
            password: password_hash($password, PASSWORD_BCRYPT)
        );

        // Guardar en el repositorio
        return $this->repository->save($user);
    }
}