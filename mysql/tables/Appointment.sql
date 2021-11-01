DROP TABLE IF EXISTS Appointment;

CREATE TABLE Appointment (
    Id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    Id_Patient INT NULL,
    Date DATE NULL,
    StartTime TIME NULL,
    EndTime TIME NULL,
    Status BOOLEAN NULL,
    Issue VARCHAR(45) NULL,

    FOREIGN KEY (Id_Patient) REFERENCES Patient(Id)
);