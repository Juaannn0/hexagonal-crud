<?php

namespace Modules\User\Domain\UseCases;

use Modules\User\Domain\Entities\User;
use Modules\User\Domain\Ports\UserRepositoryInterface;
use Modules\User\Domain\Exceptions\UserNotFoundException;

class GetUserById
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id): User
    {
        $user = $this->repository->findById($id);

        if (!$user) {
            throw UserNotFoundException::withId($id);
        }

        return $user;
    }
}