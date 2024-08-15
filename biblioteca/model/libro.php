<?php

include_once 'conexion.php';

class Book {
    private $con;

    public function __construct() {
        $database = new Database();
        $this->con = $database->connect();
    }

    // Obtener todos los libros
    public function getAll() {
        $query = '
        SELECT libros.libro_id, libros.titulo, libros.genero, libros.fecha_publicacion, libros.isbn, GROUP_CONCAT(autor_libro.autor_id) AS autores_ids
        FROM libros
        LEFT JOIN autor_libro ON libros.libro_id = autor_libro.libro_id
        GROUP BY libros.libro_id
    ';
    $stmt = $this->con->query($query);
    $resultado = array();
    if ($stmt->num_rows > 0) {
        while ($fila = $stmt->fetch_assoc()) {
            $resultado[] = $fila;
        }
    }
    return json_encode($resultado);
    }

    // Agregar un nuevo libro
    public function add($titulo, $genero, $fecha_publicacion, $isbn, $autores = []) {
        $stmt = $this->con->prepare('INSERT INTO libros (titulo, genero, fecha_publicacion, isbn) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $titulo, $genero, $fecha_publicacion, $isbn);
        $stmt->execute();
        $libro_id = $stmt->insert_id; // Obtener el ID del libro recién insertado

        // Insertar en la tabla intermedia autor_libro
        if (!empty($autores)) {
            foreach ($autores as $autor_id) {
                $stmt = $this->con->prepare('INSERT INTO autor_libro (autor_id, libro_id) VALUES (?, ?)');
                $stmt->bind_param('ii', $autor_id, $libro_id);
                $stmt->execute();
            }
        }
    }

    // Actualizar un libro existente
    public function update($id, $titulo, $genero, $fecha_publicacion, $isbn, $autores) {
        $stmt = $this->con->prepare('UPDATE libros SET titulo = ?, genero = ?, fecha_publicacion = ?, isbn = ? WHERE libro_id = ?');
        $stmt->execute([$titulo, $genero, $fecha_publicacion, $isbn, $id]);
        
        // Limpiar autores existentes
        $this->con->query('DELETE FROM autor_libro WHERE libro_id = ' . (int)$id);
        
        // Insertar nuevos autores
        foreach ($autores as $autor_id) {
            $this->con->query('INSERT INTO autor_libro (autor_id, libro_id) VALUES (' . (int)$autor_id . ', ' . (int)$id . ')');
        }
    }

    // Eliminar un libro
    public function delete($id) {
        $this->con->query('DELETE FROM autor_libro WHERE libro_id = ' . (int)$id);
        $this->con->query('DELETE FROM libros WHERE libro_id = ' . (int)$id);
    }
    // Obtener los autores de un libro
    public function getAuthors($libro_id) {
        $stmt = $this->con->prepare('
            SELECT a.* 
            FROM autores a
            JOIN autor_libro al ON a.autor_id = al.autor_id
            WHERE al.libro_id = ?');
        $stmt->bind_param('i', $libro_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $autores = array();
        while ($fila = $resultado->fetch_assoc()) {
            $autores[] = $fila;
        }
        return json_encode($autores);
    }
}
