<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 05/03/2019
 * Time: 10:16
 */

namespace Project\Users;


use DateTime;

class User
{
    public $user_id;
    public $name;
    public $surname;
    public $password;
    public $mail;
    public $token;
    public $init_date;
    public $user_type;
    public function __construct($user_id, $name, $surname, $mail, $password, $token, $init_date, $user_type)
    {
        $this->user_id = $user_id;
        $this->name = $name;
        $this->surname = $surname;
        $this->password = $password;
        $this->mail = $mail;
        $this->token = $token;
        $this->init_date = $init_date;
        $this->user_type = $user_type;
    }
    public function getToken($newUserId)
    {
        $now = new DateTime();
        $future = new DateTime("now +1 year");
        $payload = [
            "iat" => $now->getTimeStamp(),
            "exp" => $future->getTimeStamp(),
            "jti" => base64_encode(ramdom_bytes(16)),
            "id" => $newUserId,
        ];
        $token = JWT::encode($payload, $this->secret, "HS256");
        return $token;
    }

}