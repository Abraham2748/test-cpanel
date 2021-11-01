DROP PROCEDURE IF EXISTS SP_ADD_PATIENT;
DELIMITER $
CREATE PROCEDURE SP_ADD_PATIENT(
    IN _fullName VARCHAR(45),
    IN _documentNumber VARCHAR(8),
    IN _email VARCHAR(45),
    IN _address VARCHAR(45),
    IN _postalCode VARCHAR(45),
    IN _gender CHAR(1),
    IN _phoneNumber CHAR(9),
    IN _birthday DATE)
BEGIN
    INSERT INTO Patient(
        FullName,
        DocumentNumber,
        Email,
        Address,
        PostalCode,
        Gender,
        PhoneNumber,
        Birthday)
    VALUES (
        _fullName,
        _documentNumber,
        _email,
        _address,
        _postalCode,
        _gender,
        _phoneNumber,
        _birthday);
END $
DELIMITER ;
