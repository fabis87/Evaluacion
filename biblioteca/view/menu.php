<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'libros';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Biblioteca</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #333;
            overflow: hidden;
        }
        .navbar a {
            float: left;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
        .navbar a.active {
            background-color: #4CAF50;
            color: white;
        }
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="?page=libros" <?php echo $page == 'libros' ? 'class="active"' : ''; ?>>Libros</a>
        <a href="?page=autores" <?php echo $page == 'autores' ? 'class="active"' : ''; ?>>Autores</a>
    </div>

    <div class="content">
        <?php
        if ($page == 'libros') {
            include 'libros.php';
        } elseif ($page == 'autores') {
            include 'autor.php';
        }
        ?>
    </div>
</body>
</html>