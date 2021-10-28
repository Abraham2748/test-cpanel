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
            $userData = $this->getUserData($email, $password);
            if ($userData) {
                return $userData;
            } else {
                return $_responses->error_200("Wrong email or password");
            }
        } else {
            return $_responses->error_400();
        }
    }

    public function validateToken($token)
    {
        $token = substr($token, 7);
        $query = "SELECT * FROM UserToken WHERE Token = '" . $token . "' AND LastDate > DATE_SUB(UTC_TIMESTAMP, INTERVAL 1 HOUR)";
        $result = parent::getData($query);
        if (sizeof($result) == 1) {
            $query = "UPDATE UserToken SET LastDate = UTC_TIMESTAMP WHERE Token = '" . $token . "'";
            parent::nonQuery($query);
            return true;
        } else {
            return false;
        }
    }

    private function getUserData($email, $password)
    {
        $params = array(
            '_email' => $email,
            '_password' => $password
        );
        $result = parent::callProcedure('SP_LOGIN', $params);
        if (sizeof($result) == 1) {
            return $result[0];
        } else {
            return null;
        }
    }
}
