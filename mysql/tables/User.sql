DROP TABLE IF EXISTS User;

CREATE TABLE User (
    Id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    Email VARCHAR(45) NOT NULL,
    Password VARCHAR(45) NOT NULL,
    Status BOOLEAN NOT NULL
);