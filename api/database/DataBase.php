<?php

class Database
{
  public static function Connect()
  {
    try {
      return new PDO('mysql:host=localhost;dbname=crud-php;charset=UTF8', 'root', '');
    } catch (PDOException $err) {
      echo json_encode(["msg" => $err]);
      die();
    }
  }
}