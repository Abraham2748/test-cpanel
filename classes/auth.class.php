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
        $result = parent::callProcedure('SP_VALIDATE_TOKEN', array(
            '_token' => substr($token, 7),
        ));
        return $result[0]['Authorize'] == 1;
    }

    private function getUserData($email, $password)
    {
        $result = parent::callProcedure('SP_LOGIN', array(
            '_email' => $email,
            '_password' => $password
        ));
        if (count($result) == 1) {
            return $result[0];
        } else {
            return null;
        }
    }
}
