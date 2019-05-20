<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 01/04/2019
 * Time: 12:42
 */

namespace Project\Projects;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class ProjectController
{
    private $dao;
    public function __construct(ContainerInterface $container)
    {
        $this->dao = new ProjectDao($container['projectDaoInterface']);
    }
    function getAllProjects(Request $request, Response $response, array $args)
    {
        $users = $this->dao->getAll();
        return $response->withJson($users);
    }
    function getProjectById(Request $request, Response $response, array $args)
    {
        $user = $this->dao->getById($args['project_id']);
        return $response->withJson($user);
    }
    function createNewProject(Request $request, Response $response, array $args)
    {
        $requestUserId = $request->getAttribute('token')->id;
        $newProject = $request->getParsedBody();
        $newUserId = $this->dao->insertProject($requestUserId,$newProject);
        return $response->withJson($newUserId);
    }
    function deleteProject(Request $request, Response $response, array $args)
    {
        $id = $args['project_id'];
        $this->dao->deleteProject($id);
        return $response->withJson($id);
    }
    function updateProject(Request $request, Response $response, array $args)
    {
        $requestUserId = $request->getAttribute('token')->id;
        if($requestUserId === $args['creator_id']) {
            $project = $request->getParsedBody();
            $id = $this->dao->updateProject($args['project_id'],$project);
            return $response->withJson($id);
        }
        else{
            return $response->withStatus(401);
        }

    }
    public function getMyProjects(Request $request, Response $response, array $args)
    {
        $requestUserId = $request->getAttribute('token')->id;
        $projects = $this->dao->getUserCreatedProjects($requestUserId);
        return $response->withJson($projects);
    }
    public function getAllParticipated(Request $request, Response $response, array $args)
    {
        $requestUserId = $request->getAttribute('token')->id;
        $projects = $this->dao->getUserProjects($requestUserId);
        return $response->withJson($projects);
    }
}