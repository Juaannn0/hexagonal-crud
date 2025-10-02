<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= isset($user) ? 'Editar Usuario' : 'Crear Usuario' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2><?= isset($user) ? 'Editar Usuario' : 'Crear Usuario' ?></h2>
    <form method="post" action="<?= isset($user) ? '/users/update/' . esc($user->id) : '/users/store' ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= isset($user) ? esc($user->name) : '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= isset($user) ? esc($user->email) : '' ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="/users" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
