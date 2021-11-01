DROP PROCEDURE IF EXISTS SP_UPDATE_PATIENT;
DELIMITER $
CREATE PROCEDURE SP_UPDATE_PATIENT(
    IN _id INT,
    IN _fullName VARCHAR(45),
    IN _documentNumber VARCHAR(8),
    IN _email VARCHAR(45),
    IN _address VARCHAR(45),
    IN _postalCode VARCHAR(45),
    IN _gender CHAR(1),
    IN _phoneNumber CHAR(9),
    IN _birthday DATE)
BEGIN
    UPDATE Patient SET 
    FullName = _fullName,
    DocumentNumber = _documentNumber,
    Email = _email,
    Address = _address,
    PostalCode = _postalCode,
    Gender = _gender,
    PhoneNumber = _phoneNumber,
    Birthday = _birthday
    WHERE Patient.Id = _id;
END $
DELIMITER ;
