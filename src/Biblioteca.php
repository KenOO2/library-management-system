<?php
require_once 'Database.php';
require_once 'Libro.php';
require_once 'Usuario.php';
require_once 'Prestamo.php';

class Biblioteca {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Gestión de Libros
    public function agregarLibro(Libro $libro) {
        //Insertar libro en base de datos

        // Preparar la consulta SQL para insertar un nuevo libro
        $sql = "INSERT INTO libros (titulo, autor, isbn, cantidad) VALUES (:titulo, :autor, :isbn, :cantidad)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':titulo', $libro->getTitulo());
        $stmt->bindValue(':autor', $libro->getAutor());
        $stmt->bindValue(':isbn', $libro->getIsbn());
        $stmt->bindValue(':cantidad', $libro->getCantidad());
        $stmt->execute();
    }

    public function editarLibro($id, $nuevosDatos) {
        // Actualizar libro en base de datos

        // Preparar la consulta SQL para actualizar un libro existente
        $sql = "UPDATE libros SET titulo = :titulo, autor = :autor, isbn = :isbn, cantidad = :cantidad WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':titulo', $nuevosDatos['titulo']);
        $stmt->bindValue(':autor', $nuevosDatos['autor']);
        $stmt->bindValue(':isbn', $nuevosDatos['isbn']);
        $stmt->bindValue(':cantidad', $nuevosDatos['cantidad']);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    public function eliminarLibro($id) {
        //Eliminar libro de base de datos

        // Preparar la consulta SQL para eliminar un libro por su ID
        $sql = "DELETE FROM libros WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        try {
            $stmt->execute();
            return null; // sin error
        } catch (PDOException $e) {
            return "No se puede eliminar este libro porque tiene préstamos asociados.";
    }
    }

    public function obtenerLibros() {
        //Retornar lista de libros disponibles

        // Preparar la consulta SQL para obtener todos los libros
        $sql = "SELECT * FROM libros";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        // Retornar todos los libros como un arreglo asociativo
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarLibro($id) {
        //Retornar un libro específico

        // Preparar la consulta SQL para obtener un libro por su ID
        $sql = "SELECT * FROM libros WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        // Retornar el libro encontrado como un arreglo asociativo
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Gestión de Usuarios
    public function agregarUsuario(Usuario $usuario) {
        // Insertar usuario en base de datos

        //Prepara la consulta sql para insertar usuarios
        $sql = "INSERT INTO usuarios (nombre, email, telefono) VALUES (:nombre, :email, :telefono)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nombre', $usuario->getNombre());
        $stmt->bindValue(':email', $usuario->getEmail());
        $stmt->bindValue(':telefono', $usuario->getTelefono());
        $stmt->execute();
    }

    public function editarUsuario($id, $nuevosDatos) {
        //Actualizar usuario en base de datos

        //Preparar consulta sql para actualizar informacion de usuarios por Id
        $sql = "UPDATE usuarios SET nombre = :nombre, email = :email, telefono = :telefono WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nombre', $nuevosDatos['nombre']);
        $stmt->bindValue(':email', $nuevosDatos['email']);
        $stmt->bindValue(':telefono', $nuevosDatos['telefono']);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    public function eliminarUsuario($id) {
        //Eliminar usuario de base de datos

         // Preparar la consulta SQL para eliminar un Usuario por su ID
        $sql = "DELETE FROM usuarios WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        try {
            $stmt->execute();
            return null; // sin error
        } catch (PDOException $e) {
            return "No se puede eliminar este usuario porque tiene préstamos asociados.";
    }
    }

    public function obtenerUsuarios() {
        //Retornar lista de usuarios

         // Preparar la consulta SQL para obtener todos los Usuarios
        $sql = "SELECT * FROM usuarios";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        // Retornar todos los Usuarios como un arreglo asociativo
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Gestión de Préstamos
    public function prestarLibro($libro_id, $usuario_id) {
        //Crear registro de préstamo y actualizar stock de libros

        try{
            $this->conn->beginTransaction();
            //Verificar que la cantidad del libro sea mayor a cero
            $sqlVerifcacion = "SELECT * FROM libros WHERE id = :id";
            $stmt = $this->conn->prepare($sqlVerifcacion);
            $stmt->bindValue(':id', $libro_id);
            $stmt->execute();
            $libro = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($libro['cantidad'] > 0){
                $sqlInsert = "INSERT INTO prestamos (libro_id, usuario_id, fecha_prestamo, estado) VALUES (:id_libro, :id_usuario, :fecha_prestamo, 'activo')";
                $stmt2 = $this->conn->prepare($sqlInsert);
                $stmt2->bindValue(':id_libro', $libro_id);
                $stmt2->bindValue(':id_usuario', $usuario_id);
                $stmt2->bindValue(':fecha_prestamo', date('Y-m-d'));
                $stmt2->execute();

                $nuevosDatos = [
                    'titulo' => $libro['titulo'],
                    'autor' => $libro['autor'],
                    'isbn' => $libro['isbn'],
                    'cantidad' => $libro['cantidad'] - 1
                    ];
                $this->editarLibro($libro['id'], $nuevosDatos);

            }else{
                echo "No se puede realizar el prestamo cantidad disponible: ".$libro['cantidad'];
            }
            $this->conn->commit();
        }catch (PDOException $e){
            $this->conn->rollBack();
             echo "Error al prestar el libro: " . $e->getMessage();
        }
    }

    public function devolverLibro($prestamo_id) {
        //Actualizar fecha de devolución y estado del préstamo, actualizar stock
         try{
            $this->conn->beginTransaction();
             //Obtener info del prestamo
            $sqlVerifcacion = "SELECT * FROM prestamos WHERE id = :id";
            $stmt = $this->conn->prepare($sqlVerifcacion);
            $stmt->bindValue(':id', $prestamo_id);
            $stmt->execute();
            $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

            $libro = $this->buscarLibro($prestamo['libro_id']);
            
            $sqlUpdate = "UPDATE prestamos SET fecha_devolucion = :fecha_devolucion, estado = 'devuelto' WHERE id =:id";
            $stmt2 = $this->conn->prepare($sqlUpdate);
            $stmt2->bindValue(':fecha_devolucion', date('Y-m-d'));
            $stmt2->bindValue(':id', $prestamo_id);
            $stmt2->execute();

            $nuevosDatos = [
                'titulo' => $libro['titulo'],
                'autor' => $libro['autor'],
                'isbn' => $libro['isbn'],
                'cantidad' => $libro['cantidad'] + 1
                ];
            $this->editarLibro($libro['id'], $nuevosDatos);         
            $this->conn->commit();
        }catch (PDOException $e){
            $this->conn->rollBack();
             echo "Error al devolver el libro: " . $e->getMessage();
        }
    }

    public function obtenerPrestamosActivos() {
        //Retornar lista de préstamos activos
        //Preparar sconsulta sql para extraer todo los prestamos
            $sql = "SELECT * FROM prestamos WHERE estado = 'activo'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
          
        return   $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
