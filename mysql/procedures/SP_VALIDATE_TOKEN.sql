DROP PROCEDURE IF EXISTS SP_VALIDATE_TOKEN;
DELIMITER $
CREATE PROCEDURE SP_VALIDATE_TOKEN(
    IN _token VARCHAR(32))
BEGIN
    DECLARE _exists INT;
    SELECT COUNT(*) INTO _exists 
    FROM UserToken
    WHERE UserToken.Token = _token;
    IF _exists = 1 THEN
        UPDATE UserToken SET LastDate = UTC_TIMESTAMP
        WHERE UserToken.Token = _token;
        SELECT 1 AS Authorize;
    ELSE
        SELECT 0 AS Authorize;
    END IF;
END $
DELIMITER ;