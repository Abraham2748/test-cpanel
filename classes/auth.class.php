<?php

require_once "connection/connection.php";
require_once "responses.class.php";

class Auth extends Connection
{
    public function login($json)
    {
        $_responses = new responses;
        $data = json_decode($json, true);
        if (isset($data['email']) && isset($data["password"])) {
            $email = $data["email"];
            $password = md5($data["password"]);
            $userData = $this->getUserData($email);
            if ($userData) {
                if ($password == $userData[0]["Password"]) {
                    if ($userData[0]["Status"] == 1) {
                        $token = $this->insertToken($userData[0]['Id']);
                        if ($token) {
                            $result = $_responses->response;
                            $result['result'] = array(
                                "token" => $token
                            );
                            return $result;
                        } else {
                            return $_responses->error_500();
                        }
                    } else {
                        return $_responses->error_200("User email $email is not active.");
                    }
                    return $userData;
                } else {
                    return $_responses->error_200("Incorrect password");
                }
            } else {
                return $_responses->error_200("User email $email does not exist");
            }
        } else {
            return $_responses->error_400();
        }
    }

    public function validateToken($token)
    {
        $token = substr($token, 7);
        $query = "SELECT * FROM UserToken WHERE Token = '" . $token . "' AND Status = 1 AND LastDate > DATE_SUB(UTC_TIMESTAMP, INTERVAL 1 HOUR)";
        $result = parent::getData($query);
        if (sizeof($result) == 1) {
            $query = "UPDATE UserToken SET LastDate = UTC_TIMESTAMP WHERE Token = '" . $token . "'";
            parent::nonQuery($query);
            return true;
        } else {
            return false;
        }
    }

    private function getUserData($email)
    {
        $query = "SELECT * FROM User WHERE Email = '$email'";
        $data = parent::getData($query);
        if (sizeof($data) == 1) {
            return $data;
        } else {
            return null;
        }
    }

    private function insertToken($userId)
    {
        $cryptographicallyStrong = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16, $cryptographicallyStrong));
        $status = 1;
        $query = "INSERT INTO UserToken (Id_User, Token, Status, LastDate) VALUES ('$userId','$token','$status', UTC_TIMESTAMP);";
        $exist = parent::nonQuery($query);
        if ($exist) {
            return $token;
        } else {
            return false;
        }
    }
}
