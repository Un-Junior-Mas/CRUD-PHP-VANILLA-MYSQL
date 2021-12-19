<?php

include_once "../database/DataBase.php";
include_once "../models/Tasks.php";

$methods = $_SERVER['REQUEST_METHOD'];

switch ($methods) {
  case "GET":
    if (isset($_GET['id'])) {
      ReadTask();
    } else {
      ReadTasks();
    }
    break;
  case "POST":
    CrearTask();
    break;
  case "PUT":
    UpdateTask();
    break;
  case "DELETE":
    DeleteTask();
    break;
  default:
    http_response_code(404);
    break;
}

function ReadTask()
{
  if (!isset($_GET['id'])) {
    http_response_code(404);
    die();
  }

  $tasks = new Tasks();
  $tasks->setId($_GET['id']);
  $myTask = $tasks->Edit();

  if ($myTask) {
    http_response_code(200);
    echo $myTask;
  } else {
    http_response_code(404);
  }
}

function CrearTask()
{

  $request = json_decode(file_get_contents('php://input'));

  if (!$request->title || !$request->description) {
    http_response_code(404);
    die();
  }

  $tasks = new Tasks();
  $tasks->setId(uniqid());
  $tasks->setTitle($request->title);
  $tasks->setDescription($request->description);

  $result = $tasks->Create();

  if ($result) {
    http_response_code(200);
  } else {
    http_response_code(404);
  }
}

function ReadTasks()
{
  echo Tasks::All();
}

function UpdateTask()
{

  $request = json_decode(file_get_contents('php://input'));

  if (!$request->title || !$request->description || !isset($_GET['id'])) {
    http_response_code(404);
    die();
  }

  $tasks = new Tasks();
  $tasks->setId($_GET['id']);
  $tasks->setTitle($request->title);
  $tasks->setDescription($request->description);

  $result = $tasks->Update();

  if ($result) {
    http_response_code(200);
  } else {
    http_response_code(404);
  }
}

function DeleteTask()
{
  if (!isset($_GET['id'])) {
    http_response_code(404);
    die();
  }

  $tasks = new Tasks();

  $tasks->setId($_GET['id']);
  $result = $tasks->Delete();

  if ($result) {
    http_response_code(200);
  } else {
    http_response_code(404);
  }
}
