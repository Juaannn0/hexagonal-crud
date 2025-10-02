<?php

namespace Modules\User\Infrastructure\Http;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Modules\User\Domain\UseCases\CreateUser;
use Modules\User\Domain\UseCases\GetUserById;
use Modules\User\Domain\UseCases\GetAllUsers;
use Modules\User\Domain\UseCases\UpdateUser;
use Modules\User\Domain\UseCases\DeleteUser;
use Modules\User\Domain\Exceptions\UserNotFoundException;
use Modules\User\Domain\Exceptions\UserAlreadyExistsException;
use Modules\User\Infrastructure\Persistence\MySQLUserRepository;

class UserController extends ResourceController
{
    protected $format = 'json';
    // Métodos para vistas web

    /**
     * Vista: lista de usuarios (HTML)
     */
    public function listView()
    {
        $useCase = new GetAllUsers($this->repository);
        $users = $useCase->execute(100, 0);
        return view('Modules/Users/list', [
            'users' => $users
        ]);
    }

    /**
     * Vista: formulario de usuario (HTML)
     * Si se pasa $id, es edición; si no, es creación
     */
    public function formView($id = null)
    {
        $user = null;
        if ($id) {
            try {
                $useCase = new GetUserById($this->repository);
                $user = $useCase->execute((int)$id);
            } catch (UserNotFoundException $e) {
                // Si no existe, $user queda null
            }
        }
        return view('Modules/Users/form', [
            'user' => $user
        ]);
    }
    private MySQLUserRepository $repository;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->repository = new MySQLUserRepository($db);
    }

    /**
     * GET /api/users
     * Obtener todos los usuarios
     */
    public function index(): ResponseInterface
    {
        try {
            $limit = $this->request->getGet('limit') ?? 100;
            $offset = $this->request->getGet('offset') ?? 0;

            $useCase = new GetAllUsers($this->repository);
            $users = $useCase->execute((int)$limit, (int)$offset);

            $usersArray = array_map(fn($user) => $user->toArray(), $users);

            return $this->respond([
                'status' => 'success',
                'data' => $usersArray,
                'count' => count($usersArray)
            ]);
        } catch (\Exception $e) {
            return $this->failServerError('Error fetching users: ' . $e->getMessage());
        }
    }

    /**
     * GET /api/users/{id}
     * Obtener un usuario por ID
     */
    public function show($id = null): ResponseInterface
    {
        try {
            $useCase = new GetUserById($this->repository);
            $user = $useCase->execute((int)$id);

            return $this->respond([
                'status' => 'success',
                'data' => $user->toArray()
            ]);
        } catch (UserNotFoundException $e) {
            return $this->failNotFound($e->getMessage());
        } catch (\Exception $e) {
            return $this->failServerError('Error fetching user: ' . $e->getMessage());
        }
    }

    /**
     * POST /api/users
     * Crear un nuevo usuario
     */
    public function create(): ResponseInterface
    {
        try {
            $data = $this->request->getJSON(true);

            // Validación básica
            if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
                return $this->failValidationErrors('Name, email and password are required');
            }

            $useCase = new CreateUser($this->repository);
            $user = $useCase->execute(
                $data['name'],
                $data['email'],
                $data['password']
            );

            return $this->respondCreated([
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => $user->toArray()
            ]);
        } catch (UserAlreadyExistsException $e) {
            return $this->failValidationErrors($e->getMessage());
        } catch (\InvalidArgumentException $e) {
            return $this->failValidationErrors($e->getMessage());
        } catch (\Exception $e) {
            return $this->failServerError('Error creating user: ' . $e->getMessage());
        }
    }

    /**
     * PUT /api/users/{id}
     * Actualizar un usuario
     */
    public function update($id = null): ResponseInterface
    {
        try {
            $data = $this->request->getJSON(true);

            // Validación básica
            if (!isset($data['name']) || !isset($data['email'])) {
                return $this->failValidationErrors('Name and email are required');
            }

            $useCase = new UpdateUser($this->repository);
            $user = $useCase->execute(
                (int)$id,
                $data['name'],
                $data['email'],
                $data['password'] ?? null
            );

            return $this->respond([
                'status' => 'success',
                'message' => 'User updated successfully',
                'data' => $user->toArray()
            ]);
        } catch (UserNotFoundException $e) {
            return $this->failNotFound($e->getMessage());
        } catch (UserAlreadyExistsException $e) {
            return $this->failValidationErrors($e->getMessage());
        } catch (\InvalidArgumentException $e) {
            return $this->failValidationErrors($e->getMessage());
        } catch (\Exception $e) {
            return $this->failServerError('Error updating user: ' . $e->getMessage());
        }
    }

    /**
     * DELETE /api/users/{id}
     * Eliminar un usuario
     */
    public function delete($id = null): ResponseInterface
    {
        try {
            $useCase = new DeleteUser($this->repository);
            $useCase->execute((int)$id);

            return $this->respondDeleted([
                'status' => 'success',
                'message' => 'User deleted successfully'
            ]);
        } catch (UserNotFoundException $e) {
            return $this->failNotFound($e->getMessage());
        } catch (\Exception $e) {
            return $this->failServerError('Error deleting user: ' . $e->getMessage());
        }
    }
}