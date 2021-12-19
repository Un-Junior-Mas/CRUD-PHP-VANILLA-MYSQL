<?php

class Tasks
{

  private string $id;
  private string $title;
  private string $description;

  public function setId(string $id)
  {
    $this->id = $id;
  }
  public function setTitle(string $title)
  {
    $this->title = $title;
  }
  public function setDescription(string $desc)
  {
    $this->description = $desc;
  }

  //CRUD

  public static function All()
  {
    $sql = "SELECT * FROM tasks";
    $statement = Database::Connect()->prepare($sql);
    $statement->execute();

    $array = [];
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
      $array[] = $row;
    }

    return json_encode($array);
  }

  public function Create()
  {
    $sql = "INSERT INTO tasks(id,title,description) VALUES(?,?,?)";

    $statement = Database::Connect()->prepare($sql);
    $statement->bindParam(1, $this->id);
    $statement->bindParam(2, $this->title);
    $statement->bindParam(3, $this->description);
    $statement->execute();

    return $statement->rowCount();
  }

  public function Update()
  {
    $sql = "UPDATE tasks SET title=?, description=? WHERE id=?";

    $statement = Database::Connect()->prepare($sql);
    $statement->bindParam(1, $this->title);
    $statement->bindParam(2, $this->description);
    $statement->bindParam(3, $this->id);
    $statement->execute();

    return true;
  }

  public function Delete()
  {
    $sql = "DELETE FROM tasks WHERE id=?";

    $statement = Database::Connect()->prepare($sql);
    $statement->bindParam(1, $this->id);
    $statement->execute();

    return $statement->rowCount();
  }

  public function Edit()
  {
    $sql = "SELECT * FROM tasks WHERE id=?";

    $statement = Database::Connect()->prepare($sql);
    $statement->bindParam(1, $this->id);
    $statement->execute();

    return json_encode($statement->fetchObject());
  }
}
