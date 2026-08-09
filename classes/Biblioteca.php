<?php
require_once 'Database.php';
require_once 'Libro.php';
require_once 'Usuario.php';
require_once 'Prestamo.php';

class Biblioteca
{
    private $db;
    private $conn;

    public function __construct()
    {
        // Inicializar conexión a base de datos
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Gestión de Libros
    public function agregarLibro(Libro $libro)
    {
        //Insertar libro en base de datos
        $sql = "INSERT INTO libros (titulo, autor, isbn, cantidad)
        VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $libro->getTitulo(),
            $libro->getAutor(),
            $libro->getIsbn(),
            $libro->getCantidad()
        ]);
    }

    public function editarLibro($id, $nuevosDatos)
    {
        // Actualizar libro en base de datos
        $sql = "UPDATE libros
        SET titulo = ?, autor = ?, isbn = ?, cantidad = ?
        WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $nuevosDatos['titulo'],
            $nuevosDatos['autor'],
            $nuevosDatos['isbn'],
            $nuevosDatos['cantidad'],
            $id
        ]);
    }

    public function eliminarLibro($id)
    {
        // Eliminar libro de base de datos
        $sql = "DELETE FROM libros
        WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
    }

    public function obtenerLibros()
    {
        // Retornar lista de libros disponibles

        $sql = "SELECT * FROM libros";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $libros;
    }

    public function buscarLibro($id)
    {
        // Retornar un libro específico

        $sql = "SELECT * FROM libros WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        $libro = $stmt->fetch(PDO::FETCH_ASSOC);

        return $libro;
    }

    // Gestión de Usuarios
    public function agregarUsuario(Usuario $usuario)
    {
        // Insertar usuario en base de datos
        $sql = "INSERT INTO usuarios (nombre, email, telefono)
        VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $usuario->getNombre(),
            $usuario->getEmail(),
            $usuario->getTelefono()
        ]);
    }

    public function editarUsuario($id, $nuevosDatos)
    {
        // Actualizar usuario en base de datos
        $sql = "UPDATE usuarios
        SET nombre = ?, email = ?, telefono = ?
        WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $nuevosDatos['nombre'],
            $nuevosDatos['email'],
            $nuevosDatos['telefono'],
            $id
        ]);
    }

    public function eliminarUsuario($id)
    {
        // Verificar si el usuario tiene préstamos
        $sql = "SELECT COUNT(*) FROM prestamos WHERE usuario_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        $cantidadPrestamos = $stmt->fetchColumn();

        // No permitir eliminar si tiene préstamos
        if ($cantidadPrestamos > 0) {
            return false;
        }

        // Eliminar usuario
        $sql = "DELETE FROM usuarios WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        return true;
    }

    public function obtenerUsuarios()
    {
        // Retornar lista de usuarios
        $sql = "SELECT * FROM usuarios";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $usuarios;
    }
    public function buscarUsuario($id)
    {
        $sql = "SELECT * FROM usuarios WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Gestión de Préstamos
    public function prestarLibro($libro_id, $usuario_id)
    {
        // Crear registro de préstamo y actualizar stock de libros
        $sql = "INSERT INTO prestamos (libro_id, usuario_id, fecha_prestamo)
        VALUES (?, ?, CURDATE())";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $libro_id,
            $usuario_id
        ]);

        $sql = "UPDATE libros
        SET cantidad = cantidad - 1
        WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $libro_id
        ]);
    }

    public function devolverLibro($prestamo_id)
    {
        // Actualizar fecha de devolución y estado del préstamo, actualizar stock

        // Buscar el préstamo
        $sql = "SELECT * FROM prestamos WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$prestamo_id]);

        $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

        // Obtener el libro relacionado
        $libro_id = $prestamo['libro_id'];

        // Actualizar el préstamo
        $sql = "UPDATE prestamos
         SET fecha_devolucion = CURDATE(), estado = 'devuelto'
         WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$prestamo_id]);

        // Aumentar la cantidad del libro
        $sql = "UPDATE libros 
         SET cantidad = cantidad + 1 
         WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$libro_id]);
    }

    public function obtenerPrestamosActivos()
    {
        // Retornar lista de préstamos activos
        $sql = "SELECT prestamos.*, libros.titulo, usuarios.nombre
            FROM prestamos
            JOIN libros ON prestamos.libro_id = libros.id
            JOIN usuarios ON prestamos.usuario_id = usuarios.id
            WHERE prestamos.estado = 'activo'";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $prestamos;
    }
}
