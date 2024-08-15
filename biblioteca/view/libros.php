<?php

include_once __DIR__ . '/../model/libro.php';
include_once __DIR__ . '/../model/autor.php';

$book = new Book();
$autor = new Autor();

// Manejar las acciones del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $book->add($_POST['titulo'], $_POST['genero'], $_POST['fecha_publicacion'], $_POST['isbn'], $_POST['autores']);
    } elseif (isset($_POST['update'])) {
        $book->update($_POST['libro_id'], $_POST['titulo'], $_POST['genero'], $_POST['fecha_publicacion'], $_POST['isbn'], $_POST['autores']);
    } elseif (isset($_POST['delete'])) {
        $book->delete($_POST['libro_id']);
    }
}

// Obtener todos los libros
$libros = json_decode($book->getAll(), true);

// Obtener todos los autores para el formulario de agregar/editar
$autores = json_decode($autor->getAll(), true);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Libros</title>
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
        input, select, button {
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
        .selected-authors {
            margin-bottom: 10px;
        }
        .selected-authors li {
            background: #e9ecef;
            padding: 5px;
            margin-bottom: 5px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestión de Libros</h1>

        <!-- Formulario para agregar/editar libros -->
        <form method="POST">
            <input type="hidden" name="libro_id" id="libro_id">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" id="titulo" required>
            <label for="genero">Género:</label>
            <input type="text" name="genero" id="genero" required>
            <label for="fecha_publicacion">Fecha de Publicación:</label>
            <input type="date" name="fecha_publicacion" id="fecha_publicacion" required>
            <label for="isbn">ISBN:</label>
            <input type="text" name="isbn" id="isbn" required>
            <label for="autores">Autores:</label>
            <select name="autores[]" id="autores" multiple>
                <?php foreach ($autores as $autor): ?>
                    <option value="<?php echo $autor['autor_id']; ?>"><?php echo $autor['nombre'] . ' ' . $autor['apellido']; ?></option>
                <?php endforeach; ?>
            </select>
            <ul id="selected-authors" class="selected-authors"></ul>
            <button type="submit" name="add" id="submitBtn">Agregar Libro</button>
        </form>

        <!-- Tabla de libros -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Género</th>
                    <th>Fecha de Publicación</th>
                    <th>ISBN</th>
                    <th>Autores</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($libros as $libro): ?>
                    <tr>
                        <td><?php echo $libro['libro_id']; ?></td>
                        <td><?php echo $libro['titulo']; ?></td>
                        <td><?php echo $libro['genero']; ?></td>
                        <td><?php echo $libro['fecha_publicacion']; ?></td>
                        <td><?php echo $libro['isbn']; ?></td>
                        <td><?php echo $libro['autores_ids'] ? implode(', ', array_map(function($id) use ($autores) {
                            $autor = array_filter($autores, fn($a) => $a['autor_id'] == $id);
                            return $autor ? reset($autor)['nombre'] . ' ' . reset($autor)['apellido'] : 'Desconocido';
                        }, explode(',', $libro['autores_ids']))) : 'No hay autores'; ?></td>
                        <td class="actions">
                            <button class="edit" onclick="editarLibro(<?php echo htmlspecialchars(json_encode($libro)); ?>)">Editar</button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="libro_id" value="<?php echo $libro['libro_id']; ?>">
                                <button type="submit" name="delete" class="delete" onclick="return confirm('¿Estás seguro de que quieres eliminar este libro?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function editarLibro(libro) {
            document.getElementById('libro_id').value = libro.libro_id;
            document.getElementById('titulo').value = libro.titulo;
            document.getElementById('genero').value = libro.genero;
            document.getElementById('fecha_publicacion').value = libro.fecha_publicacion;
            document.getElementById('isbn').value = libro.isbn;

            // Cargar autores seleccionados
            let selectedAuthors = libro.autores_ids ? libro.autores_ids.split(',') : [];
            let authorSelect = document.getElementById('autores');
            let selectedAuthorsList = document.getElementById('selected-authors');

            selectedAuthorsList.innerHTML = '';
            for (let option of authorSelect.options) {
                if (selectedAuthors.includes(option.value)) {
                    option.selected = true;
                    selectedAuthorsList.innerHTML += `<li>${option.text}</li>`;
                } else {
                    option.selected = false;
                }
            }

            document.getElementById('submitBtn').name = 'update';
            document.getElementById('submitBtn').textContent = 'Actualizar Libro';
        }

        document.getElementById('autores').addEventListener('change', function() {
            let selectedAuthorsList = document.getElementById('selected-authors');
            selectedAuthorsList.innerHTML = '';

            for (let option of this.options) {
                if (option.selected) {
                    selectedAuthorsList.innerHTML += `<li>${option.text}</li>`;
                }
            }
        });
    </script>
</body>
</html>


