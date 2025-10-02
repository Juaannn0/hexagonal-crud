<?php

namespace Modules\User\Domain\UseCases;

use Modules\User\Domain\Entities\User;
use Modules\User\Domain\ValueObjects\Email;
use Modules\User\Domain\Ports\UserRepositoryInterface;
use Modules\User\Domain\Exceptions\UserNotFoundException;
use Modules\User\Domain\Exceptions\UserAlreadyExistsException;

class UpdateUser
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(
        int $id,
        string $name,
        string $email,
        ?string $password = null
    ): User {
        // Buscar el usuario
        $user = $this->repository->findById($id);

        if (!$user) {
            throw UserNotFoundException::withId($id);
        }

        // Validar email único (excluyendo el usuario actual)
        $emailVO = new Email($email);
        if ($this->repository->emailExists($emailVO->getValue(), $id)) {
            throw UserAlreadyExistsException::withEmail($email);
        }

        // Actualizar datos
        $user->setName($name);
        $user->setEmail($emailVO);
        
        if ($password !== null && !empty($password)) {
            $user->setPassword($password);
        }

        $user->updateTimestamp();

        // Guardar cambios
        return $this->repository->update($user);
    }
}