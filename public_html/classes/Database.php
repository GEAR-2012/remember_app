<?php

class Database
{
    // production database info (000webhost)
    private $serverName = 'localhost';
    private $dBUsername = 'id17724645_remember_admin';
    private $dBPassword = '3MAbLJ8|2r&Lq9DT';
    private $dBName = 'id17724645_remember';

    // development databse info
    // private $serverName = 'localhost';
    // private $dBUsername = 'root';
    // private $dBPassword = 'TurtleDove01';
    // private $dBName = 'remember';

    // connection obj
    protected $conn;

    public function __construct()
    {
        // if database connection error return error
        // connect to a database
        $conn = mysqli_connect(
            $this->serverName,
            $this->dBUsername,
            $this->dBPassword,
            $this->dBName
        );

        // check connection & define the 'conn' property
        if (!$conn) {
            return 'Database connection error: ' . mysqli_connect_error();
        } else {
            $this->conn = $conn;
        }
    }

    public function __destruct()
    {
        // close connection
        mysqli_close($this->conn);
    }
}
