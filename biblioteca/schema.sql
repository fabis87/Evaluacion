CREATE DATABASE biblioteca;
USE biblioteca;

CREATE TABLE libros (
    libro_id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    genero VARCHAR(100),
    fecha_publicacion DATE,
    isbn VARCHAR(13)
);


CREATE TABLE autores (
    autor_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE,
    nacionalidad VARCHAR(50)
);

CREATE TABLE autor_libro (
    autor_id INT,
    libro_id INT,
    FOREIGN KEY (autor_id) REFERENCES autores(autor_id),
    FOREIGN KEY (libro_id) REFERENCES libros(libro_id),
    PRIMARY KEY (autor_id, libro_id)
);