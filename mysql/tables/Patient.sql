DROP TABLE IF EXISTS Patient;

CREATE TABLE Patient (
    Id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    FullName VARCHAR(45) NULL,
    DocumentNumber VARCHAR(8) NULL,
    Email VARCHAR(45) NULL,
    Address VARCHAR(45) NULL,
    PostalCode VARCHAR(45) NULL,
    Gender CHAR(1) NULL,
    PhoneNumber CHAR(9) NULL,
    Birthday DATE NULL
);