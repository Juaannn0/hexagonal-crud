<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Usuarios</h2>
    <a href="/users/create" class="btn btn-primary mb-3">Agregar Usuario</a>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php if (isset($users) && count($users) > 0): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= esc($user->id) ?></td>
                    <td><?= esc($user->name) ?></td>
                    <td><?= esc($user->email) ?></td>
                    <td>
                        <a href="/users/edit/<?= esc($user->id) ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="/users/delete/<?= esc($user->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4" class="text-center">No hay usuarios registrados.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
