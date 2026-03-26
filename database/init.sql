DROP DATABASE IF EXISTS proyecto_hospital;
CREATE DATABASE IF NOT EXISTS proyecto_hospital;
USE proyecto_hospital;

CREATE TABLE areas (
    id_area INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    planta VARCHAR(20) NOT NULL,
    descripcion TEXT NULL
);

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM ('celador', 'facultativo') NOT NULL,
    id_area INT NULL,
    CONSTRAINT fk_usuario_area
        FOREIGN KEY (id_area)
        REFERENCES areas(id_area)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE TABLE pacientes (
    id_paciente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(150) NOT NULL,
    habitacion VARCHAR(20),
    planta_habitacion VARCHAR(20),
    observaciones TEXT
);

CREATE TABLE ubicaciones (
    id_ubicacion INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    planta VARCHAR(20) NOT NULL,
    zona VARCHAR(5),             
    habitacion VARCHAR(10),      
    pos_x INT,
    pos_y INT,
    descripcion TEXT
);

CREATE TABLE traslados (
    id_traslado INT AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT NOT NULL,
    id_origen INT NOT NULL,
    id_destino INT NOT NULL,
    id_celador INT NULL,
    id_facultativo INT NOT NULL,
    facultativo_solicitante VARCHAR(100),
    estado ENUM ('pendiente', 'en_curso', 'completado', 'cancelado', 'pospuesto') NOT NULL DEFAULT 'pendiente',
    fecha_solicitud DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    prueba_solicitada VARCHAR(100) NULL,

    CONSTRAINT fk_traslado_paciente
        FOREIGN KEY (id_paciente)
        REFERENCES pacientes(id_paciente)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_traslado_origen
        FOREIGN KEY (id_origen)
        REFERENCES ubicaciones(id_ubicacion)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_traslado_destino
        FOREIGN KEY (id_destino)
        REFERENCES ubicaciones(id_ubicacion)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_traslado_celador
        FOREIGN KEY (id_celador)
        REFERENCES usuarios(id_usuario)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    CONSTRAINT fk_traslado_facultativo
        FOREIGN KEY (id_facultativo)
        REFERENCES usuarios(id_usuario)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

CREATE TABLE historial_traslados (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,
    id_traslado INT NULL,
    estado VARCHAR(50) NOT NULL,
    comentario TEXT,
    fecha_cambio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_historial_traslado
        FOREIGN KEY (id_traslado)
        REFERENCES traslados(id_traslado)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);