<?php
include 'Database.php';

class UserAuth extends Database
{
    public function isUsernameExists($user_name)
    {
        // Search for username, if exists return true, if not return false
        // if no database connection return error
        // This is for new user registration
        if ($this->conn):
            $result;
    
        // create query string
        $sql = "SELECT name FROM users WHERE name=?;";
    
        // prepared statement
        $stmt = mysqli_prepare($this->conn, $sql);
    
        // bind parameters for markers
        mysqli_stmt_bind_param($stmt, 's', $user_name);
    
        // execute query
        mysqli_stmt_execute($stmt);
    
        // store result in an internal buffer
        mysqli_stmt_store_result($stmt);
    
        if (mysqli_stmt_num_rows($stmt) === 0) {
            // if user name not found
            $result = false;
        } else {
            $result = true;
        }

        // free result
        mysqli_stmt_free_result($stmt);

        // close statement
        mysqli_stmt_close($stmt);

        return $result; else:
            return 'No database connection';
        endif;
    }

    public function getUser($user_name_email)
    {
        if ($this->conn):
        // Search for username or useremail, if exists return user, if not return false
        // if no database connection return error
        // This is for user login
        $result;

        // create query string
        $sql = "SELECT id, name, email, pwd
                FROM users
                WHERE name=? OR email=?;";

        // prepared statement
        $stmt = mysqli_prepare($this->conn, $sql);

        // bind parameters for markers
        mysqli_stmt_bind_param($stmt, 'ss', $user_name_email, $user_name_email);

        // execute query
        mysqli_stmt_execute($stmt);

        // bind the returned result to variables
        mysqli_stmt_bind_result($stmt, $user_id, $user_name, $user_email, $user_pwd);
        
        
        // check for any matches
        if (mysqli_stmt_fetch($stmt)) {
            $user = [
                'user_id' => $user_id,
                'user_name' => $user_name,
                'user_email' => $user_email,
                'user_pwd' => $user_pwd
            ];
            return $user;
        } else {
            $result = false;
        }

        // close statement
        mysqli_stmt_close($stmt);

        return $result; else:
            return 'No database connection';
        endif;
    }

    public function insertNewUser($name, $email, $pwd)
    {
        if ($this->conn):
        // Insert new user into database, if success return true, if not return false
        // if no database connection return error
        // This is for user registration
        $result;

        // hash the password
        $hash = password_hash($pwd, PASSWORD_DEFAULT);
  
        // create query string
        $sql = "INSERT INTO users (name, email, pwd)
                VALUES (?, ?, ?);";

        $stmt = mysqli_stmt_init($this->conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            return 'Database error';
        }

        mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $hash);
  
        // execute query
        if (mysqli_stmt_execute($stmt)) {
            $result = true;
        } else {
            $result = false;
        }

        mysqli_stmt_close($stmt);

        return $result; else:
            return 'No database connection';
        endif;
    }
}
