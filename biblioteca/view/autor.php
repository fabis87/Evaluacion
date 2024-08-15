<?php

include_once __DIR__ . '/../model/autor.php';

$autor = new Autor();

// Manejar las acciones del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $autor->add($_POST['nombre'], $_POST['apellido'], $_POST['fecha_nacimiento'], $_POST['nacionalidad']);
    } elseif (isset($_POST['update'])) {
        $autor->update($_POST['autor_id'], $_POST['nombre'], $_POST['apellido'], $_POST['fecha_nacimiento'], $_POST['nacionalidad']);
    } elseif (isset($_POST['delete'])) {
        $autor->delete($_POST['autor_id']);
    }
}

// Obtener todos los autores
$autores = json_decode($autor->getAll(), true);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Autores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            margin: 20px 0;
            color: #333;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        form {
            background: #fff;
            border-radius: 8px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input, button {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ddd;
            width: calc(100% - 22px);
            box-sizing: border-box;
        }
        button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .actions button {
            margin-right: 5px;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
        }
        .actions .edit {
            background-color: #28a745;
        }
        .actions .edit:hover {
            background-color: #218838;
        }
        .actions .delete {
            background-color: #dc3545;
        }
        .actions .delete:hover {
            background-color: #c82333;
        }
        .actions form {
            display: inline;
        }
        .error-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestión de Autores</h1>

        <!-- Formulario para agregar/editar autores -->
        <form method="POST">
            <input type="hidden" name="autor_id" id="autor_id">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" required>
            <label for="apellido">Apellido:</label>
            <input type="text" name="apellido" id="apellido" required>
            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required>
            <label for="nacionalidad">Nacionalidad:</label>
            <input type="text" name="nacionalidad" id="nacionalidad" required>
            <button type="submit" name="add" id="submitBtn">Agregar Autor</button>
        </form>

        <!-- Tabla de autores -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Nacionalidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($autores as $autor): ?>
                    <tr>
                        <td><?php echo $autor['autor_id']; ?></td>
                        <td><?php echo $autor['nombre']; ?></td>
                        <td><?php echo $autor['apellido']; ?></td>
                        <td><?php echo $autor['fecha_nacimiento']; ?></td>
                        <td><?php echo $autor['nacionalidad']; ?></td>
                        <td class="actions">
                            <button class="edit" onclick="editarAutor(<?php echo htmlspecialchars(json_encode($autor)); ?>)">Editar</button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="autor_id" value="<?php echo $autor['autor_id']; ?>">
                                <button type="submit" name="delete" class="delete" onclick="return confirm('¿Estás seguro de que quieres eliminar este autor?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function editarAutor(autor) {
            document.getElementById('autor_id').value = autor.autor_id;
            document.getElementById('nombre').value = autor.nombre;
            document.getElementById('apellido').value = autor.apellido;
            document.getElementById('fecha_nacimiento').value = autor.fecha_nacimiento;
            document.getElementById('nacionalidad').value = autor.nacionalidad;
            document.getElementById('submitBtn').name = 'update';
            document.getElementById('submitBtn').textContent = 'Actualizar Autor';
        }
    </script>
</body>
</html>
