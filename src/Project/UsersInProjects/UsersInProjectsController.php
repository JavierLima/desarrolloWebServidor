<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 01/05/2019
 * Time: 16:37
 */

namespace Project\UsersInProjects;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class UsersInProjectsController
{
    private $dao;
    public function __construct(ContainerInterface $container)
    {
        $this->dao = new UsersInProjectsDao($container['projectDaoInterface']);
    }

    public function insertUserInProject(Request $request, Response $response, array $args)
    {
        $requestUserId = $request->getAttribute('token')->id;
        $args = $request->getParsedBody();
        if($this->dao->existsUserInTable($args['project_id'],$requestUserId) == false) {
            $id = $this->dao->insertUserInProject($args['project_id'], $requestUserId);
            return $response->withJson($id);
        }
         else
            return $response->withStatus(500);


    }
    public function deleteUserInProject(Request $request, Response $response, array $args)
    {
        $requestUserId = $request->getAttribute('token')->id;
        $this->dao->deleteUserInProject($args['project_id'],$requestUserId);
        return $response->withJson($args['project_id'],$requestUserId);
    }
}