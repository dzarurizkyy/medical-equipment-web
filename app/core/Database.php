<?php  
  class Database {
    private $dbh,
            $stmt,
            $host = DBHOST,
            $user = DBUSER,
            $pass = DBPASS,
            $name = DBNAME;
    
    // Initialize database connection
    public function __construct() {
      $dsn = "mysql:host={$this->host}; dbname={$this->name}";

      $option = [
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION
      ];

      try {
        $this->dbh = new PDO($dsn, $this->user, $this->pass);
      } catch(PDOExeception $e) {
        die($e->getMessage());
      }
    }

    // Prepare SQL query for execution
    public function query($query) {
      $this->stmt = $this->dbh->prepare($query);
    }

    // Bind value to parameter in the prepared statement
    public function bind($param, $value, $type = null) {
      if(is_null($type)) {
        switch(true) {
          case is_int($value) :
            $type = PDO::PARAM_INT;
            break;
          case is_bool($value) :
            $type = PDO::PARAM_BOOL;
            break;
          case is_null($value) :
            $type = PDO::PARAM_NULL;
            break;
          default:
            $type = PDO::PARAM_STR;  
        }
      }

      $this->stmt->bindValue($param, $value, $type);
    }

    // Execute prepared statement
    public function execute() {
      $this->stmt->execute();
    }

    // Fetch all results 
    public function resultSet() {
      $this->execute();
      return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch single result
    public function single() {
      $this->execute();
      return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get the number of rows affected
    public function rowCount() {
      return $this->stmt->rowCount();
    }
  }
?>