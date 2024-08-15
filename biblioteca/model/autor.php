<?php

include_once 'conexion.php';

class Autor {
    private $con;

    public function __construct() {
        $database = new Database();
        $this ->con = $database->connect();
    }

    // Obtener todos los autores
    public function getAll() {
       $stmt = $this->con->query('SELECT * FROM autores');
       $resultado = array();
        if($stmt->num_rows > 0){
            while($fila = $stmt->fetch_assoc()){
                $resultado[] = $fila;
            }
        }
        return json_encode($resultado);
    }

    public function add($nombre, $apellido, $fecha_nacimiento, $nacionalidad) {
        $stmt = $this->con->prepare('INSERT INTO autores (nombre, apellido, fecha_nacimiento, nacionalidad) VALUES (?, ?, ?, ?)');
        $stmt->execute([$nombre, $apellido, $fecha_nacimiento, $nacionalidad]);
    }

    public function update($id, $nombre, $apellido, $fecha_nacimiento, $nacionalidad) {
        $stmt = $this->con->prepare('UPDATE autores SET nombre = ?, apellido = ?, fecha_nacimiento = ?, nacionalidad = ? WHERE autor_id = ?');
        $stmt->execute([$nombre, $apellido, $fecha_nacimiento, $nacionalidad, $id]);
    }

    public function delete($id) {
        $stmt = $this->con->prepare('DELETE FROM autores WHERE autor_id = ?');
        try{
            $stmt->execute([$id]);
        }catch(Exception $e){
            echo '<p style="color: red;"> No se puede eliminar el autor porque está asociado a uno o más libros.</p>';
        }
    }
}



