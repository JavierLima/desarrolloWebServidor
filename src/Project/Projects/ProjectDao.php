<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 01/04/2019
 * Time: 12:42
 */

namespace Project\Projects;

use Project\Utils\ProjectDaoInterface;

class ProjectDao
{
    private $dbConnection;
    public function __construct(ProjectDaoInterface $dbConeection)
    {
        $this->dbConnection = $dbConeection;
    }

    public function getAll()
    {
        $sql = "SELECT project_id, tittle, description, NProgrammers - (SELECT COUNT(*) FROM USERS_IN_PROJECTS UP1, USERS U1 WHERE U1.USER_ID = UP1.USER_ID AND UP1.PROJECT_ID = p.PROJECT_ID AND U1.USER_TYPE = 1) as NProgrammers,
                NDesigners - (SELECT COUNT(*) FROM USERS_IN_PROJECTS UP1, USERS U1 
                          WHERE U1.USER_ID = UP1.USER_ID AND UP1.PROJECT_ID = p.PROJECT_ID AND U1.USER_TYPE = 2) as NDesigners,
                NAnimators - (SELECT COUNT(*) FROM USERS_IN_PROJECTS UP1, USERS U1 
                          WHERE U1.USER_ID = UP1.USER_ID AND UP1.PROJECT_ID = p.PROJECT_ID AND U1.USER_TYPE = 3) as NAnimators,
                limit_date FROM Projects p";
        return $this->dbConnection->fetchAll($sql);
    }
    public function getById($id)
    {
        $sql = "SELECT * FROM Projects WHERE project_id= ?";
        return $this->dbConnection->fetch($sql, array($id));
    }
    public function updateProject($projectId, $project)
    {
        $sql = "UPDATE Projects SET ";
        $params = array();
        $sqlProjectData = "";
        $allowed = ["tittle","description","NProgrammers","NDesigners","NAnimators","limit_date"];

        foreach ($allowed as $key){
            if(isset($project[$key])){
                $sqlProjectData .= "$key = ?,";
                array_push($params,$project[$key]);
            }
        }
        $sqlProjectData = rtrim($sqlProjectData,",");
        $sqlProjectData .= " WHERE project_id = ?";
        array_push($params,$projectId);

        $sql = $sql . $sqlProjectData;
        $this->dbConnection->execute($sql,$params);
    }

    public function deleteProject($id)
    {
        $sql = "DELETE FROM Projects where project_id = ?";
        $this->dbConnection->execute($sql,array($id));
    }
    public function insertProject($userId,$newProject)
    {
        $sql = "INSERT INTO Projects (creator_id, tittle, description, NProgrammers,	NDesigners, NAnimators, limit_date) values (?, ?, ?, ?, ?, ?, ?)";
        return $this->dbConnection->insert($sql,array($userId,$newProject['tittle'],$newProject['description'],$newProject['NProgrammers'],$newProject['NDesigners'],$newProject['NAnimators'],$newProject['limit_date']));
    }
    public function getUserCreatedProjects($requestUserId)
    {
        $sql = "SELECT p.project_id, p.tittle, p.description,
                p.NProgrammers - (SELECT COUNT(*) FROM USERS_IN_PROJECTS UP1, USERS U1 
                          WHERE U1.USER_ID = UP1.USER_ID AND p.PROJECT_ID = UP1.PROJECT_ID  AND U1.USER_TYPE = 1) AS NProgrammers,
                p.NDesigners - (SELECT COUNT(*) FROM USERS_IN_PROJECTS UP1, USERS U1 
                          WHERE U1.USER_ID = UP1.USER_ID AND p.PROJECT_ID = UP1.PROJECT_ID   AND U1.USER_TYPE = 2) AS NDesigners,
                p.NAnimators - (SELECT COUNT(*) FROM USERS_IN_PROJECTS UP1, USERS U1 
                          WHERE U1.USER_ID = UP1.USER_ID AND p.PROJECT_ID = UP1.PROJECT_ID  AND U1.USER_TYPE = 3) AS NAnimators,
                p.limit_date FROM `projects` p RIGHT JOIN users_in_projects UP1 ON UP1.user_id = p.creator_id AND p.project_id = UP1.project_id ";
        return $this->dbConnection->fetchAll($sql, array($requestUserId));
    }
    public function getUserProjects($requestUserId)
    {
        $sql = "SELECT p.project_id, p.tittle, p.description,
                p.NProgrammers - (SELECT COUNT(*) FROM USERS_IN_PROJECTS UP1, USERS U1 
                          WHERE U1.USER_ID = UP1.USER_ID AND p.PROJECT_ID = UP1.PROJECT_ID  AND U1.USER_TYPE = 1) AS NProgrammers,
                p.NDesigners - (SELECT COUNT(*) FROM USERS_IN_PROJECTS UP1, USERS U1 
                          WHERE U1.USER_ID = UP1.USER_ID AND p.PROJECT_ID = UP1.PROJECT_ID   AND U1.USER_TYPE = 2) AS NDesigners,
                p.NAnimators - (SELECT COUNT(*) FROM USERS_IN_PROJECTS UP1, USERS U1 
                          WHERE U1.USER_ID = UP1.USER_ID AND p.PROJECT_ID = UP1.PROJECT_ID  AND U1.USER_TYPE = 3) AS NAnimators,
                p.limit_date FROM `projects` p RIGHT JOIN users_in_projects UP1 ON UP1.user_id = ? AND p.project_id = UP1.project_id";
        return $this->dbConnection->fetchAll($sql, array($requestUserId));
    }
}