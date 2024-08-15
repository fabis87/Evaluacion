<?php

class Database {
    private $host = 'localhost'; 
    private $port = '3306';      
    private $user = 'root';
    private $pass = 'root';
    private $dbname = 'biblioteca';

    
    public function connect() {
      $con = mysqli_connect("{$this->host}:{$this->port}", $this->user, $this->pass, $this->dbname);

        if (!$con) {
            // Captura la excepción y muestra el mensaje de error
            throw new Exception("Error en la conexión: " . mysqli_connect_error());
        }
        return $con;
       
    }
}

