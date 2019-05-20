<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 05/03/2019
 * Time: 10:10
 */

namespace Project\Users;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class UserController
{
    private $dao;
    public function __construct(ContainerInterface $container)
    {
        $this->dao = new UserDao($container['projectDaoInterface']);
    }
    function getAllUsers(Request $request, Response $response, array $args)
    {
        $users = $this->dao->getAll();
        return $response->withJson($users);
    }
    function getUserById(Request $request, Response $response, array $args)
    {
        $user = $this->dao->getById($args['user_id']);
        return $response->withJson($user);
    }
    function createNewUser(Request $request, Response $response, array $args)
    {
        $newUser = $request->getParsedBody();
        $newUser['password'] = password_hash($newUser['password'], PASSWORD_DEFAULT);
        $newUserId = $this->dao->insertUser($newUser);

        $user = $this->dao->getById($newUserId);
        $user->token = $this->dao->generateToken($user->user_id);
        return $response->withJson($user);
    }
    function deleteUser(Request $request, Response $response, array $args)
    {
        $user_id = $args['user_id'];
        $this->dao->deleteUser($user_id);
        return $response->withJson($user_id);
    }
    function updateUser(Request $request, Response $response, array $args)
    {
        $requestUserId = $request->getAttribute('token')->id;
        $user_id = $args['user_id'];
        if($user_id === $requestUserId){
            $user = $request->getParsedBody();
            $this->dao->updateUser($user_id,$user);
            $user =$this->dao->getById($user_id);
            $user->token = $this->dao->generateToken($user->user_id);
            return $response->withJson($user);
        }
        else {
            return $response->withStatus(401);
        }
    }
    function loginUser(Request $request, Response $response, array $args)
    {
        $requestUser = $request->getParsedBody();

        $user = $this->dao->getUser($requestUser['mail']);
        $hash = $user->password;
        if($requestUser['mail'] === $user->mail) {
            if (password_verify($requestUser['password'], $hash)){
                $user->token = $this->dao->generateToken($user->user_id);
                return $response->withJson($user);
            }
            else {
                return $response->withStatus(401);
            }
        }
        else {
            return $response->withStatus(401);
        }
    }

}

