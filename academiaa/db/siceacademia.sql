create database if not exists siceacademia;
USE siceacademia;

create table estudiantes (
    id INT auto_increment primary key,
    nombre varchar(100) NOT NULL,
    apellido varchar(100) NOT NULL,
    email varchar(100),
    telefono varchar(20),
    usuario varchar(50),
    contrasena varchar(50),
    tipo varchar(20) DEFAULT 'alumno'
);

create table grupos (
    id INT auto_increment primary key,
    nombre varchar(100) NOT NULL
);

create table materias (
    id INT auto_increment primary key,
    nombre varchar(100) NOT NULL,
    creditos INT
);

create table alumno_grupo (
    id INT auto_increment primary key,
    alumno_id INT,
    grupo_id INT,
    foreign key (alumno_id) REFERENCES estudiantes(id),
    foreign key (grupo_id) REFERENCES grupos(id)
);

create table profesor_grupo_materia (
    id INT auto_increment primary key,
    profesor_id INT,
    grupo_id INT,
    materia_id INT,
    foreign key (profesor_id) REFERENCES estudiantes(id),
    foreign key (grupo_id) REFERENCES grupos(id),
    foreign key (materia_id) REFERENCES materias(id)
);

create table calificaciones (
    id int auto_increment primary key,
    estudiante_id INT,
    materia_id INT,
    calificacion FLOAT,
    foreign key (estudiante_id) REFERENCES estudiantes(id),
    foreign key (materia_id) REFERENCES materias(id)
);

insert into estudiantes (nombre, apellido, email, telefono, usuario, contrasena, tipo)
values ('Jairo', 'soriano', 'jairo@uabjo.com', '9511914378', 'jairo', 'j4ir0', 'alumno');

insert into estudiantes (nombre, apellido, email, telefono, usuario, contrasena, tipo)
values ('Profesor', 'jirafales', 'profesor@uabjo.com', '9512500101', 'profesor', 'pr0f3', 'profesor');

insert into estudiantes (nombre, apellido, email, telefono, usuario, contrasena, tipo)
values ('Admin', 'general', 'admin@uabjo.com', '9510000000', 'admin', '4dm1n', 'administrador');

insert into estudiantes (nombre, apellido, email, telefono, usuario, contrasena, tipo)
values ('Coordinador', 'academico', 'coord@uabjo.com', '9511111111', 'coord', 'c00rd', 'coordinador');

insert into grupos (nombre) values ('Grupo A'), ('Grupo B');
