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
        $params = array(
            '_token' => $token,
        );
        $result = parent::callProcedure('SP_VALIDATE_TOKEN', $params);
        return $result['authorize'] == 1;
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
