<?php
require_once 'classes/Biblioteca.php';
require_once 'classes/Libro.php';
require_once 'classes/Usuario.php';


// Instanciar la clase Biblioteca
$biblioteca = new Biblioteca();

// Manejar lógica de enrutamiento o acciones (GET/POST)
$action = $_GET['action'] ?? 'libros';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'crear_libro') {

    $libro = new Libro(
        $_POST['titulo'],
        $_POST['autor'],
        $_POST['isbn'],
        $_POST['cantidad']
    );

    $biblioteca->agregarLibro($libro);

    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'editar_usuario') {

    $biblioteca->editarUsuario(
        $_POST['id'],
        [
            'nombre' => $_POST['nombre'],
            'email' => $_POST['email'],
            'telefono' => $_POST['telefono']
        ]
    );

    header('Location: index.php?action=usuarios');
    exit;
}

if ($action === 'eliminar_libro' && isset($_GET['id'])) {
    $biblioteca->eliminarLibro($_GET['id']);

    header('Location: index.php');
    exit;
}

if ($action === 'eliminar_usuario' && isset($_GET['id'])) {

    $eliminado = $biblioteca->eliminarUsuario($_GET['id']);

    if (!$eliminado) {
        echo "<script>
                alert('No se puede eliminar el usuario porque tiene préstamos registrados.');
                window.location.href = 'index.php?action=usuarios';
              </script>";
        exit;
    }

    header('Location: index.php?action=usuarios');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'editar_libro') {

    $biblioteca->editarLibro(
        $_POST['id'],
        [
            'titulo' => $_POST['titulo'],
            'autor' => $_POST['autor'],
            'isbn' => $_POST['isbn'],
            'cantidad' => $_POST['cantidad']
        ]
    );

    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'crear_usuario') {

    $usuario = new Usuario(
        $_POST['nombre'],
        $_POST['email'],
        $_POST['telefono']
    );

    $biblioteca->agregarUsuario($usuario);

    header('Location: index.php?action=usuarios');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'prestar_libro') {

    $biblioteca->prestarLibro(
        $_POST['libro_id'],
        $_POST['usuario_id']
    );

    header('Location: index.php?action=prestamos');
    exit;
}

if ($action === 'devolver_libro' && isset($_GET['id'])) {

    $biblioteca->devolverLibro($_GET['id']);

    header('Location: index.php?action=prestamos');
    exit;
}

$libros = $biblioteca->obtenerLibros();
$usuarios = $biblioteca->obtenerUsuarios();
$prestamos = $biblioteca->obtenerPrestamosActivos();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Biblioteca</title>
    <style>
        /* TODO: Agregar estilos CSS */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        nav {
            margin-bottom: 20px;
            background: #eee;
            padding: 10px;
        }

        nav a {
            margin-right: 15px;
            text-decoration: none;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php if ($action === 'editar_libro' && isset($_GET['id'])): ?>

            <?php $libro = $biblioteca->buscarLibro($_GET['id']); ?>

            <h2>Editar libro</h2>

            <form method="POST" action="index.php?action=editar_libro">

                <input type="hidden" name="id" value="<?= $libro['id'] ?>">

                <label>Título:</label>
                <input
                    type="text"
                    name="titulo"
                    value="<?= $libro['titulo'] ?>"
                    required>

                <label>Autor:</label>
                <input
                    type="text"
                    name="autor"
                    value="<?= $libro['autor'] ?>"
                    required>

                <label>ISBN:</label>
                <input
                    type="text"
                    name="isbn"
                    value="<?= $libro['isbn'] ?>"
                    required>

                <label>Cantidad:</label>
                <input
                    type="number"
                    name="cantidad"
                    value="<?= $libro['cantidad'] ?>"
                    min="1"
                    required>

                <button type="submit">Guardar cambios</button>

            </form>

            <a href="index.php">Cancelar</a>

        <?php endif; ?>
        <?php if ($action === 'libros'): ?>

            <h2>Libros</h2>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titulo</th>
                        <th>Autor</th>
                        <th>ISBN</th>
                        <th>Cantidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($libros as $libro): ?>
                        <tr>
                            <td><?= $libro['id'] ?></td>
                            <td><?= $libro['titulo'] ?></td>
                            <td><?= $libro['autor'] ?></td>
                            <td><?= $libro['isbn'] ?></td>
                            <td><?= $libro['cantidad'] ?></td>

                            <td>
                                <a href="index.php?action=editar_libro&id=<?= $libro['id'] ?>">
                                    Editar
                                </a>

                                <a href="index.php?action=eliminar_libro&id=<?= $libro['id'] ?>">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h2>Agregar libro</h2>

            <form method="POST" action="index.php?action=crear_libro">

                <label>Título:</label>
                <input type="text" name="titulo" required>

                <label>Autor:</label>
                <input type="text" name="autor" required>

                <label>ISBN:</label>
                <input type="text" name="isbn" required>

                <label>Cantidad:</label>
                <input type="number" name="cantidad" min="1" required>

                <button type="submit">Agregar libro</button>

            </form>

        <?php endif; ?>

        <?php if ($action === 'editar_usuario'): ?>

            <?php $usuario = $biblioteca->buscarUsuario($_GET['id']); ?>

            <h2>Editar usuario</h2>

            <form method="POST" action="index.php?action=editar_usuario">

                <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

                <label>Nombre:</label>
                <input
                    type="text"
                    name="nombre"
                    value="<?= $usuario['nombre'] ?>"
                    required>

                <label>Email:</label>
                <input
                    type="email"
                    name="email"
                    value="<?= $usuario['email'] ?>"
                    required>

                <label>Teléfono:</label>
                <input
                    type="text"
                    name="telefono"
                    value="<?= $usuario['telefono'] ?>"
                    required>

                <button type="submit">Guardar cambios</button>

            </form>

            <a href="index.php?action=usuarios">Cancelar</a>

        <?php endif; ?>
        <?php if ($action === 'usuarios'): ?>

            <h2>Usuarios</h2>

            <h2>Agregar usuario</h2>

            <form method="POST" action="index.php?action=crear_usuario">
                <label>Nombre:</label>
                <input type="text" name="nombre" required>

                <label>Email:</label>
                <input type="email" name="email" required>

                <label>Teléfono:</label>
                <input type="text" name="telefono" required>

                <button type="submit">Agregar usuario</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Telefono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= $usuario['id'] ?></td>
                            <td><?= $usuario['nombre'] ?></td>
                            <td><?= $usuario['email'] ?></td>
                            <td><?= $usuario['telefono'] ?></td>

                            <td>
                                <a href="index.php?action=editar_usuario&id=<?= $usuario['id'] ?>">
                                    Editar
                                </a>

                                <a href="index.php?action=eliminar_usuario&id=<?= $usuario['id'] ?>">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>

        <?php if ($action === 'prestamos'): ?>
            <h2>Préstamos Activos</h2>

            <h2>Prestar libro</h2>

            <form method="POST" action="index.php?action=prestar_libro">

                <label>Libro:</label>

                <select name="libro_id" required>
                    <?php foreach ($libros as $libro): ?>
                        <?php if ($libro['cantidad'] > 0): ?>
                            <option value="<?= $libro['id'] ?>">
                                <?= $libro['titulo'] ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>

                <label>Usuario:</label>

                <select name="usuario_id" required>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?= $usuario['id'] ?>">
                            <?= $usuario['nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Prestar libro</button>

            </form>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Libro</th>
                        <th>Usuario</th>
                        <th>Fecha Préstamo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($prestamos as $prestamo): ?>
                        <tr>
                            <td><?= $prestamo['id'] ?></td>
                            <td><?= $prestamo['titulo'] ?></td>
                            <td><?= $prestamo['nombre'] ?></td>
                            <td><?= $prestamo['fecha_prestamo'] ?></td>
                            <td><?= $prestamo['estado'] ?></td>
                            <td>
                                <a href="index.php?action=devolver_libro&id=<?= $prestamo['id'] ?>">
                                    Devolver
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>
    </div>
</body>

</html>