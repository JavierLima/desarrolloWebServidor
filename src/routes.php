<?php

use Project\Users\UserController;
use Project\Projects\ProjectController;
use Project\UsersInProjects\UsersInProjectsController;


// Routes
$authentication = $app->getContainer()->get('authentication');

$app->get('/user', UserController::class . ':getAllUsers');
$app->get('/user/{user_id}',UserController::class . ':getUserById')->add($authentication);
$app->put('/user/{user_id}',UserController::class . ':updateUser')->add($authentication);
$app->post('/register',UserController::class . ':createNewUser');
$app->post('/login',UserController::class . ':loginUser');
$app->delete('/user/{user_id}',UserController::class . ':deleteUser');

$app->get('/projects', ProjectController::class . ':getAllProjects');
$app->get('/project/{project_id}',ProjectController::class . ':getProjectById');
$app->get('/myProjects', ProjectController::class . ':getMyProjects')->add($authentication);
$app->get('/participatedProjects', ProjectController::class . ':getAllParticipated')->add($authentication);
$app->put('/project/{project_id}/{creator_id}',ProjectController::class . ':updateProject')->add($authentication);
$app->post('/project',ProjectController::class . ':createNewProject')->add($authentication);
$app->delete('/project/{project_id}',ProjectController::class . ':deleteProject')->add($authentication);


$app->post('/userInProject',UsersInProjectsController::class . ':insertUserInProject')->add($authentication);
$app->delete('/userInProject/{project_id}',UsersInProjectsController::class . ':deleteUserInProject')->add($authentication);


