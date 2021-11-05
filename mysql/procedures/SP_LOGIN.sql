DROP PROCEDURE IF EXISTS SP_LOGIN;
DELIMITER $
CREATE PROCEDURE SP_LOGIN(
    IN _email VARCHAR(45),
    IN _password CHAR(32))
BEGIN
    DECLARE _id INT;
    DECLARE _token VARCHAR(32);
    SELECT User.Id INTO _id FROM User
    WHERE User.Email = _email 
    AND User.Password = _password;
    IF _id IS NOT NULL THEN
        SET _token = MD5(RAND());
        INSERT INTO UserToken (Id_User, Token, LastDate)
        VALUES (_id, _token, UTC_TIMESTAMP());
        SELECT *, _token AS Token FROM User WHERE User.Id = _id;
    END IF;
END $
DELIMITER ;
