<?php
session_start();

require_once 'src/Biblioteca.php';

$biblioteca = new Biblioteca();

// Manejo de formularios (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['accion_form'] == 'agregar_libro') {
        $libro = new Libro($_POST['titulo'], $_POST['autor'], $_POST['isbn'], $_POST['cantidad']);
        $biblioteca->agregarLibro($libro);
        
        header('Location: index.php?action=libros');
        exit;
    }
    elseif ($_POST['accion_form'] == 'agregar_usuario') {
        $usuario = new Usuario($_POST['nombre'], $_POST['email'], $_POST['telefono']);
        $biblioteca->agregarUsuario($usuario);
        
        header('Location: index.php?action=usuarios');
        exit;
    }
    elseif ($_POST['accion_form'] == 'devolver_libro') {
        $biblioteca->devolverLibro($_POST['prestamo_id']);
        
        header('Location: index.php?action=prestamos');
        exit;
    }
    elseif ($_POST['accion_form'] == 'prestar_libro') {
        $biblioteca->prestarLibro($_POST['libro_id'], $_POST['usuario_id']);
        
        header('Location: index.php?action=prestamos');
        exit;
    }
    elseif ($_POST['accion_form'] == 'eliminar_libro') {
    $error = $biblioteca->eliminarLibro($_POST['libro_id']);
    
        if ($error) {
            $_SESSION['mensaje_error'] = $error;
        }
    
        header('Location: index.php?action=libros');
        exit;
    }
    elseif ($_POST['accion_form'] == 'eliminar_usuario') {
        $error = $biblioteca->eliminarUsuario($_POST['usuario_id']);
    
        if ($error) {
            $_SESSION['mensaje_error'] = $error;
        }
    
    header('Location: index.php?action=usuarios');
    exit;
    }
    elseif ($_POST['accion_form'] == 'editar_libro') {
        $nuevosDatos = [
            'titulo' => $_POST['titulo'],
            'autor' => $_POST['autor'],
            'isbn' => $_POST['isbn'],
            'cantidad' => $_POST['cantidad']
        ];
    
        $biblioteca->editarLibro($_POST['libro_id'], $nuevosDatos);
    
        header('Location: index.php?action=libros');
        exit;
    }
    elseif ($_POST['accion_form'] == 'editar_usuario') {
        $nuevosDatos = [
            'nombre' => $_POST['nombre'],
            'email' => $_POST['email'],
            'telefono' => $_POST['telefono']
        ];
    
        $biblioteca->editarUsuario($_POST['usuario_id'], $nuevosDatos);
    
        header('Location: index.php?action=usuarios');
        exit;
    }
}


// Enrutamiento de lectura (GET)
$action = $_GET['action'] ?? 'libros';
$datos = [];

if ($action == 'libros') {
    $datos = $biblioteca->obtenerLibros();
} elseif ($action == 'usuarios') {
    $datos = $biblioteca->obtenerUsuarios();
} elseif ($action == 'prestamos') {
    $datos = $biblioteca->obtenerPrestamosActivos();
}

// Lista de usuarios disponible para el <select> del formulario de préstamo
$usuariosParaPrestamo = $biblioteca->obtenerUsuarios();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Biblioteca</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            background: #f4f6f8;
            color: #2b2f36;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 30px 20px 60px;
        }

        h1 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 1.2rem;
            color: #444;
            margin-top: 30px;
            margin-bottom: 12px;
        }

        /* Navegación */
        nav {
            margin: 20px 0 25px;
            background: #2b2f36;
            padding: 12px 16px;
            border-radius: 8px;
        }

        nav a {
            margin-right: 20px;
            text-decoration: none;
            color: #f4f6f8;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 6px 4px;
            border-bottom: 2px solid transparent;
            transition: border-color 0.15s ease;
        }

        nav a:hover {
            border-color: #4f9dde;
        }

        /* Mensaje de error */
        .alerta {
            background: #fdecea;
            border: 1px solid #f4b4ab;
            color: #a12d21;
            padding: 10px 14px;
            border-radius: 6px;
            margin: 15px 0;
            font-size: 0.9rem;
        }

        /* Tarjeta contenedora para formularios */
        .card {
            background: #fff;
            border: 1px solid #e2e5ea;
            border-radius: 8px;
            padding: 18px 20px;
            margin-bottom: 25px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .card label {
            display: inline-block;
            font-size: 0.85rem;
            color: #555;
            margin: 6px 10px 6px 0;
        }

        .card input[type="text"],
        .card input[type="email"],
        .card input[type="number"] {
            display: block;
            margin-top: 4px;
            padding: 7px 9px;
            border: 1px solid #cfd4da;
            border-radius: 5px;
            font-size: 0.9rem;
            min-width: 180px;
        }

        /* Tablas */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            font-size: 0.88rem;
        }

        thead {
            background: #2b2f36;
        }

        thead th {
            color: #f4f6f8;
            text-align: left;
            padding: 10px 12px;
            font-weight: 600;
        }

        tbody td {
            padding: 8px 12px;
            border-bottom: 1px solid #eef0f2;
            vertical-align: middle;
        }

        tbody tr:nth-child(even) {
            background: #fafbfc;
        }

        tbody tr:hover {
            background: #f1f5f9;
        }

        /* Formularios de fila (prestar/editar/eliminar/devolver) */
        td form {
            display: inline-block !important;
            margin: 2px 4px 2px 0;
        }

        td input[type="text"],
        td input[type="number"],
        td select {
            padding: 4px 6px;
            border: 1px solid #cfd4da;
            border-radius: 4px;
            font-size: 0.82rem;
        }

        /* Botones */
        button {
            border: none;
            border-radius: 5px;
            padding: 6px 12px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.15s ease;
        }

        button:hover {
            opacity: 0.85;
        }

        .card button[type="submit"] {
            background: #2f80c4;
            color: #fff;
            margin-top: 10px;
            padding: 8px 18px;
        }

        /* Botón "Prestar" (primer form de cada fila de libros) */
        td form:nth-of-type(1) button {
            background: #2f9e5c;
            color: #fff;
        }

        /* Botón "Guardar" (segundo form de cada fila) */
        td form:nth-of-type(2) button {
            background: #d99a1f;
            color: #fff;
        }

        /* Botón "Eliminar" / "Devolver" (tercer form) */
        td form:nth-of-type(3) button {
            background: #d0433f;
            color: #fff;
        }

        /* En la tabla de préstamos solo hay un form (Devolver) */
        .tabla-prestamos td form button {
            background: #2f80c4;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 Biblioteca Mini-App</h1>

        <?php if (isset($_SESSION['mensaje_error'])): ?>
            <div class="alerta">⚠️ <?php echo $_SESSION['mensaje_error']; ?></div>
            <?php unset($_SESSION['mensaje_error']); ?>
        <?php endif; ?>
        
        <nav>
            <a href="index.php">Inicio / Libros</a>
            <a href="index.php?action=usuarios">Usuarios</a>
            <a href="index.php?action=prestamos">Préstamos</a>
        </nav>

        <div id="content">
            
            <?php if ($action == 'libros'): ?>
                <h2>Agregar nuevo libro</h2>
                <div class="card">
                    <form method="POST" action="index.php">
                        <input type="hidden" name="accion_form" value="agregar_libro">

                        <label>Título<input type="text" name="titulo" required></label>
                        <label>Autor<input type="text" name="autor" required></label>
                        <label>ISBN<input type="text" name="isbn"></label>
                        <label>Cantidad<input type="number" name="cantidad" value="1" min="0"></label>

                        <div><button type="submit">Agregar</button></div>
                    </form>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>ISBN</th>
                            <th>Cantidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($datos as $item): ?>
                        <tr>
                            <td><?php echo $item['id']; ?></td>
                            <td><?php echo $item['titulo']; ?></td>
                            <td><?php echo $item['autor']; ?></td>
                            <td><?php echo $item['isbn']; ?></td>
                            <td><?php echo $item['cantidad']; ?></td>
                            <td>
                                <form method="POST" action="index.php">
                                    <input type="hidden" name="accion_form" value="prestar_libro">
                                    <input type="hidden" name="libro_id" value="<?php echo $item['id']; ?>">

                                    <select name="usuario_id" required>
                                        <?php foreach ($usuariosParaPrestamo as $u): ?>
                                            <option value="<?php echo $u['id']; ?>"><?php echo $u['nombre']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit">Prestar</button>
                                </form>
                                <form method="POST" action="index.php">
                                    <input type="hidden" name="accion_form" value="editar_libro">
                                    <input type="hidden" name="libro_id" value="<?php echo $item['id']; ?>">

                                    <input type="text" name="titulo" value="<?php echo $item['titulo']; ?>" style="width:80px;">
                                    <input type="text" name="autor" value="<?php echo $item['autor']; ?>" style="width:80px;">
                                    <input type="text" name="isbn" value="<?php echo $item['isbn']; ?>" style="width:60px;">
                                    <input type="number" name="cantidad" value="<?php echo $item['cantidad']; ?>" style="width:50px;">

                                    <button type="submit">Guardar</button>
                                </form>
                                <form method="POST" action="index.php">
                                    <input type="hidden" name="accion_form" value="eliminar_libro">
                                    <input type="hidden" name="libro_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table> 

            <?php elseif ($action == 'usuarios'): ?>
                <h2>Agregar nuevo usuario</h2>
                <div class="card">
                    <form method="POST" action="index.php">
                        <input type="hidden" name="accion_form" value="agregar_usuario">

                        <label>Nombre<input type="text" name="nombre" required></label>
                        <label>Email<input type="email" name="email" required></label>
                        <label>Teléfono<input type="text" name="telefono"></label>

                        <div><button type="submit">Agregar</button></div>
                    </form>
                </div>

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
                        <?php foreach($datos as $item): ?>
                            <tr>
                                <td><?php echo $item['id']; ?></td>
                                <td><?php echo $item['nombre']; ?></td>
                                <td><?php echo $item['email']; ?></td>
                                <td><?php echo $item['telefono']; ?></td>
                            <td>
                                <form method="POST" action="index.php">
                                    <input type="hidden" name="accion_form" value="editar_usuario">
                                    <input type="hidden" name="usuario_id" value="<?php echo $item['id']; ?>">
                                    
                                    <input type="text" name="nombre" value="<?php echo $item['nombre']; ?>" style="width:80px;">
                                    <input type="text" name="email" value="<?php echo $item['email']; ?>" style="width:80px;">
                                    <input type="text" name="telefono" value="<?php echo $item['telefono']; ?>" style="width:60px;">
                                    
                                    <button type="submit">Guardar</button>
                                </form>
                                <form method="POST" action="index.php">
                                    <input type="hidden" name="accion_form" value="eliminar_usuario">
                                    <input type="hidden" name="usuario_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit">Eliminar</button>
                                </form>
                            </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table> 

            <?php elseif ($action == 'prestamos'): ?>
                <h2>Préstamos activos</h2>
                <table class="tabla-prestamos">
                    <thead>
                         <tr>
                            <th>ID</th>
                            <th>Libro ID</th>
                            <th>Usuario ID</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Finalización</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($datos as $item): ?>
                            <tr>
                                <td><?php echo $item['id']; ?></td>
                                <td><?php echo $item['libro_id']; ?></td>
                                <td><?php echo $item['usuario_id']; ?></td>
                                <td><?php echo $item['fecha_prestamo']; ?></td>
                                <td><?php echo $item['fecha_devolucion'] ?? '--'; ?></td>
                                <td><?php echo $item['estado']; ?></td>
                            <td>
                                <form method="POST" action="index.php">
                                    <input type="hidden" name="accion_form" value="devolver_libro">
                                    <input type="hidden" name="prestamo_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit">Devolver</button>
                                </form>
                            </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table> 
            <?php endif; ?>
           
            
        </div>
    </div>
</body>
</html>