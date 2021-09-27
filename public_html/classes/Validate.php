<?php

class Validate
{
    public function userName($name)
    {
        // returns error message or false
        $name =  $this->cleanInput($name);
        // check for user name format
        if (strlen($name) < 3) {
            return 'At least 3 character, please';
        }
        if (strlen($name) > 32) {
            return 'Max 32 character, please';
        }
        if (!preg_match("/^[a-zA-Z0-9-' ]*$/", $name)) {
            return 'Letters, numbers, dash, apostrophe & space only, please';
        }
        return false;
    }

    public function email($email)
    {
        // returns error message or false
        $email =  $this->cleanInput($email);
        // check for email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Invalid email format';
        }
        return false;
    }

    public function pwd($pwd)
    {
        // returns error message or false
        if (strlen($pwd) < 8) {
            // check for password min length
            return 'At least 8 character, please';
        } elseif (strlen($pwd) > 32) {
            // check for password max length
            return 'Max 32 character, please';
        } elseif (!preg_match('/\d+/', $pwd)) {
            // check for included numbers
            return 'At least one number, please';
        } elseif (!preg_match('/[a-z]+/', $pwd)) {
            // check for lowercase letter
            return 'At least one lowercase letter, please';
        } elseif (!preg_match('/[A-Z]+/', $pwd)) {
            // check for uppercase letter
            return 'At least one uppercase letter, please';
        }
        return false;
    }

    public function cleanInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}
