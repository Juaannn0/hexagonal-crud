<?php

namespace Modules\User\Domain\UseCases;

use Modules\User\Domain\Ports\UserRepositoryInterface;
use Modules\User\Domain\Exceptions\UserNotFoundException;

class DeleteUser
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id): bool
    {
        if (!$this->repository->exists($id)) {
            throw UserNotFoundException::withId($id);
        }

        return $this->repository->delete($id);
    }
}