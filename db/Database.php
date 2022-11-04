<?php
require_once("config.php");

class Database
{
    const TYPE_ROW_COUNT = 0;
    const TYPE_ASSOC_ARRAY = 1;
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $port = DB_PORT;

    private $dbname;
    protected $table;

    public $mysqli;
    public $error;

    private $isConnected = false;

    public function __construct($table = null, $dbname = DB_NAME)
    {
        $this->mysqli = Database::connectDB($this->host, $this->user, $this->pass, $dbname, $this->port);
        if ($this->mysqli->connect_error) {
            echo "Not connected, error: " . $this->mysqli->connect_error;
            exit();
        } else {
            $this->isConnected = true;
        }
        $this->dbname = $this->mysqli->real_escape_string($dbname);
        if ($table != null) {
            $this->table = $this->mysqli->real_escape_string($table);
        }
    }

    public function getTableName()
    {
        return $this->table;
    }

    private static function connectDB($host, $user, $pass, $dbname, $port)
    {
        $mysqli = new mysqli($host, $user, $pass, $dbname, $port);
        $mysqli->set_charset("utf8");
        $mysqli->set_ssl(null, null, null, "DigiCertGlobalRootCA.crt.pem", null);
        return $mysqli;
    }


    public function createTable($columns)
    {
        $query = "CREATE TABLE IF NOT EXISTS `" . $this->dbname . "`.`" . $this->table .
            "` (" . implode(", ", $columns) . ") ";
        return $this->executeUpdate($query);
    }

    public function isTableExists($tblName)
    {
        $table_exists = "SELECT * FROM information_schema.tables
                        WHERE table_schema = '$this->dbname' AND table_name = '$tblName' LIMIT 1;";
        $result = $this->mysqli->query($table_exists) or die($this->mysqli->error . __LINE__);
        if ($result->num_rows <= 0) {
            return false;
        }
        return true;
    }

    public function query($projection, $selection, $selectionArgs, $groupBy, $orderBy, $limit)
    {
        $query = "SELECT ";
        if ($projection != null) {
            $query .= implode(", ", $projection) . " ";
        } else {
            $query .= "* ";
        }
        $query .= "FROM " . $this->dbname . "." . $this->getTableName() . " ";
        if ($selection != null) {
            if ($selectionArgs != null) {
                foreach ($selectionArgs as $selectionArg) {
                    $pos = strpos("?", $selection);
                    if ($pos !== false) {
                        $selection = substr_replace($selection, $selectionArg, $pos, strlen("?"));
                    }
                }
            }
            $query .= "WHERE " . $selection . " ";
        }
        if ($groupBy != null) {
            $query .= "GROUP BY " . $groupBy . " ";
        }
        if ($orderBy != null) {
            $query .= "ORDER BY " . $orderBy . " ";
        }
        if ($limit != null) {
            $query .= "LIMIT " . $limit . " ";
        }
        return $this->executeQuery($query);
    }


    public function update($columnValue, $selection, $selectionArgs, $limit)

    {
        $query = "UPDATE " . $this->dbname . "." . $this->getTableName() . " SET ";
        $updates = [];
        foreach ($columnValue as $key => $value) {
            $updates[] = $key . " = " . "\"" . $this->mysqli->real_escape_string($value) . "\"";
        }
        $query .= implode(", ", $updates) . " ";
        if ($selection != null) {
            if ($selectionArgs != null) {
                foreach ($selectionArgs as $selectionArg) {
                    $pos = strpos("?", $selection);
                    if ($pos !== false) {
                        $selection = substr_replace($selection, $selectionArg, $pos, strlen("?"));
                    }
                }
            }
            $query .= "WHERE " . $selection . " ";
        }
        if ($limit != null) {
            $query .= "LIMIT " . $limit . " ";
        }
        return $this->executeUpdate($query);
    }

    public function delete($selection, $selectionArgs, $orderBy, $limit)
    {
        $query = "DELETE FROM " . $this->dbname . "." . $this->getTableName() . " ";
        if ($selection != null) {
            if ($selectionArgs != null) {
                foreach ($selectionArgs as $selectionArg) {
                    $pos = strpos("?", $selection);
                    if ($pos !== false) {
                        $selection = substr_replace($selection, $selectionArg, $pos, strlen("?"));
                    }
                }
            }
            $query .= "WHERE " . $selection . " ";
        }
        if ($orderBy != null) {
            $query .= "ORDER BY " . $orderBy . " ";
        }
        if ($limit != null) {
            $query .= "LIMIT " . $limit . " ";
        }
        return $this->executeUpdate($query);
    }

    public function insert($columnValue)
    {
        foreach ($columnValue as $key => $value) {
            $columnValue[$this->mysqli->real_escape_string($key)] = "\"" . $this->mysqli->real_escape_string($value) . "\"";
        }
        $columns = "(" . implode(", ", array_keys($columnValue)) . ")";
        $values = "VALUES (" . implode(", ", array_values($columnValue)) . ")";
        $query = "INSERT INTO " . $this->dbname . "." . $this->getTableName() . " " . $columns . " " . $values . ";";
        return $this->executeUpdate($query);
    }


    public function executeUpdate($query)
    {
        if (!$this->isConnected){
            return -1;
        }
        $result = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        if ($result) {
            return $this->mysqli->affected_rows;
        } else {
            return -1;
        }
    }

    public function executeQuery($query)
    {
        if (!$this->isConnected){
            return null;
        }
        $result = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        if ($result != false) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return null;
        }
    }

    public function __destruct()
    {
        mysqli_close($this->mysqli);
    }

}
