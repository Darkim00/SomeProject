<?php

class DB_Connector
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "Darkimoo@312";
    private $database = "test";
    public $connection;

    // Constructor
    public function __construct()
    {
        $this->connect();
    }

    // Connect to the database
    public function connect()
    {
        $this->connection = new mysqli($this->servername, $this->username, $this->password, $this->database);

        // Check connection
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    // Close the database connection
    public function close()
    {
        $this->connection->close();
    }
}

?>
