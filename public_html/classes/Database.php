<?php

class Database
{
    // production database info
    private $serverName = 'localhost';
    private $dBUsername = 'forevods_admin01';
    private $dBPassword = 'RememberAdmin01';
    private $dBName = 'forevods_remember';

    // development databse info
    // private $serverName = 'localhost';
    // private $dBUsername = 'rememberAdmin01';
    // private $dBPassword = 'rememberAdmin01';
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
