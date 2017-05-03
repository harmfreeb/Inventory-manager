<?php
require_once('includes/config.php');

class database {

    private $con;
    public $query_id;

    function __construct() {
      $this->db_connect();
    }


public function db_connect()
{
  $this->con = mysqli_connect(DB_HOST,DB_USER,DB_PASS);
  if(!$this->con)
         {
           die(" Database connection failed:". mysqli_connect_error());
         } else {
           $select_db = $this->con->select_db(DB_NAME);
             if(!$select_db)
             {
               die("Failed to Select Database". mysqli_connect_error());
             }
         }
}


public function query($sql)
   {

      if (trim($sql != "")) {
          $this->query_id = $this->con->query($sql);
      }
      if (!$this->query_id)
        die("Error on Query");
       return $this->query_id;

   }


public function fetch_array($statement)
{
  return mysqli_fetch_array($statement);
}

public function fetch_assoc($statement)
{
  return mysqli_fetch_assoc($statement);
}

public function affected_rows()
{
  return mysqli_affected_rows($this->con);
}

 public function escape($str){
   return $this->con->real_escape_string($str);
 }


public function resultsInArray($loop){
   $results = array();
   while ($result = $this->fetch_array($loop)) {
      $results[] = $result;
   }
 return $results;
}

}

$db = new database();

?>
