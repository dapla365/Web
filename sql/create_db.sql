--  ROLES
CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    rol VARCHAR(255) NOT NULL
);

INSERT INTO roles(rol) VALUES ('ROLE_USER');
INSERT INTO roles(rol) VALUES ('ROLE_ADMIN');


--  USUARIOS
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol INT DEFAULT 1 NOT NULL
);
ALTER TABLE usuarios ADD CONSTRAINT ROL FOREIGN KEY (rol) REFERENCES roles(id) ON DELETE RESTRICT ON UPDATE RESTRICT;

--  TOKENS
CREATE TABLE tokens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(50) NOT NULL UNIQUE,
    token INT(5) NOT NULL,
    creado_en VARCHAR(50) NOT NULL
);

