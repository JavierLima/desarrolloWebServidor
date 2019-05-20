<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 05/03/2019
 * Time: 10:10
 */

namespace Project\Users;

use DateTime;
use Firebase\JWT\JWT;
use Project\Utils\ProjectDaoInterface;


class UserDao
{
    private $dbConnection;
    public function __construct(ProjectDaoInterface $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function createUser($newUser)
    {
        $sql = "INSERT INTO Users (name, surname, password, mail, token, init_date, user_type) values (?, ?, ?, ?, ?, ? ,?)";
        $id = $this->dbConnection->insert($sql,array($newUser['name'],$newUser['password'],$newUser['mail'],$newUser['token'],$newUser['init_date'],$newUser['user_type']));
        return $this->getById($id);
    }

    public function getAll()
    {
        //$sql = "SELECT * FROM Users";
        //return $this->dbConnection->fetchAll($sql);
        $users = [];
        for ($i = 0; $i < 5; $i++) {
            $user = new User($i, "User $i", "user$i@mail.com","$i","$i");
            $users[$i] = $user;
        }
        return $users;
    }
    public function getById($id)
    {
        $sql = "SELECT * FROM Users WHERE user_id= ?";
        return $this->dbConnection->fetch($sql, array($id));
    }

    public function getUser($mail)
    {
        $sql = "SELECT * FROM Users WHERE mail= ?";
        return $this->dbConnection->fetch($sql, array($mail));
    }
    public function updateUser($userId, $user)
    {
        $sql = "UPDATE Users SET ";
        $params = array();
        $sqlUserData = "";
        $allowed = ["name","surname","mail", "user_type"];

        foreach ($allowed as $key){
            if($user[$key] != ''){
                $sqlUserData .= "$key = ?,";
                array_push($params,$user[$key]);

            }
        }
        $sqlUserData = rtrim($sqlUserData,",");
        $sqlUserData .= " WHERE user_id = ?";
        array_push($params,$userId);

        $sql = $sql . $sqlUserData;
        $this->dbConnection->execute($sql,$params);
    }

    public function deleteUser($id)
    {
        $sql = "DELETE FROM Users where user_id = ?";
        $this->dbConnection->execute($sql,array($id));
    }
    public function insertUser($newUser)
    {
        $sql = "INSERT INTO Users (name, surname, password, mail, init_date, user_type) values (?, ?, ?, ?, ? ,?)";
        $id = $this->dbConnection->insert($sql,array($newUser['name'],$newUser['surname'],$newUser['password'],$newUser['mail'],$newUser['init_date'],$newUser['user_type']));

        return $id;
    }
    public function generateToken($userID)
    {
        $now = new DateTime();
        $future = new DateTime("now +1 year");

        $payload = [
            "iat" => $now->getTimeStamp(),
            "exp" => $future->getTimeStamp(),
            "jti" => base64_encode(random_bytes(16)),
            'iss' => 'localhost:80',  // Issuer
            "id" => $userID,
        ];

        $secret = 'my secret';
        $token = JWT::encode($payload, $secret, "HS256");

        return $token;
    }
}