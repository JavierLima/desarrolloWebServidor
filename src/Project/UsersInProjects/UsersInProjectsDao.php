<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 01/05/2019
 * Time: 16:37
 */

namespace Project\UsersInProjects;

use DateTime;
use Firebase\JWT\JWT;
use Project\Utils\ProjectDaoInterface;

class UsersInProjectsDao
{
    private $dbConnection;
    public function __construct(ProjectDaoInterface $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function insertUserInProject($projectId,$userId)
    {
        $sql = "INSERT INTO users_in_projects (project_id, user_id) values (?, ?)";
        $id = $this->dbConnection->insert($sql,array($projectId,$userId));
        return $id;
    }
    public function existsUserInTable($projectId,$userId)
    {
        $sql = "SELECT user_id FROM  users_in_projects WHERE project_id=? AND user_id=? ";
        $id = $this->dbConnection->fetch($sql,array($projectId,$userId));
        return $id;
    }
    public function deleteUserInProject($projectId,$userId)
    {
        $sql = "DELETE FROM users_in_projects where project_id = ? AND user_id=?";
        $this->dbConnection->execute($sql,array($projectId,$userId));
    }

}