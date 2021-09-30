<?php
include 'UserAuth.php';

class Lists extends UserAuth
{
    public function createNewTasklist($userId, $tasklistName)
    {
        // Creates a new tasklist linked with a user id
        // returns true on success or false on failure
        $result;
        // create a query stirng
        $sql = "INSERT INTO tasklists (name, user_id)
                VALUES (?, ?);";

        // prepared statement
        $stmt = mysqli_prepare($this->conn, $sql);

        // bind parameters for markers
        mysqli_stmt_bind_param($stmt, 'si', $tasklistName, $userId);

        // execute query
        if (mysqli_stmt_execute($stmt)) {
            $result = true;
        } else {
            $result = false;
        }

        // close statement
        mysqli_stmt_close($stmt);

        return $result;
    }

    public function getUserCollection($userId)
    {
        // get user's tasklist name, fullcount & done/true count from database
        // all rows as an assoc array
        // and push all into an array & return it on succes
        // return false on failure
        $taslListCollection = [];
        // step 1. get all tasklist from user
        $sql = "SELECT
                tl.id AS 'tasklist_id', 
                tl.name AS 'tasklist_name'
                FROM tasklists AS tl
                INNER JOIN users AS u ON tl.user_id = u.id
                WHERE u.id = ?;";
        $stmt = mysqli_prepare($this->conn, $sql);
        // if ($stmt = mysqli_prepare($this->conn, $sql)) {
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        while ($tasklist = mysqli_fetch_assoc($result)) {
            // step 2. extract id & name of the next tasklist
            $tasklist_id = $tasklist['tasklist_id'];
            $tasklist_name = $tasklist['tasklist_name'];
            $countAll;
            $countTrue;
            // step 3. get the count of all task in tasklist
            $sql = "SELECT COUNT(task_status) AS countAll
                    FROM tasks
                    WHERE list_id = ?";
            if ($stmt = mysqli_prepare($this->conn, $sql)) {
                mysqli_stmt_bind_param($stmt, 'i', $tasklist_id);
                mysqli_stmt_execute($stmt);
                $result2 = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_assoc($result2)) {
                    $countAll = $row['countAll'];
                }
            }
            mysqli_stmt_close($stmt);
            
            // step 4. get the count of 'done' task in tasklist
            $sql = "SELECT COUNT(task_status) AS countTrue
                    FROM tasks
                    WHERE list_id = ? AND task_status = true";
            if ($stmt = mysqli_prepare($this->conn, $sql)) {
                mysqli_stmt_bind_param($stmt, 'i', $tasklist_id);
                mysqli_stmt_execute($stmt);
                $result3 = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_assoc($result3)) {
                    $countTrue = $row['countTrue'];
                }
            }
            mysqli_stmt_close($stmt);

            // step 5. make the result assoc array
            $data = [
                'tasklist_id' => $tasklist_id,
                'tasklist_name' => $tasklist_name,
                'count_all' => $countAll,
                'count_true' => $countTrue
                ];


            //step 6. push into the return data
            array_push($taslListCollection, $data);
        }

        // }
        return $taslListCollection;
    }

    public function getOneTaskList($userId, $taskListId)
    {
        // on succes:
        // returns a tasklist based on user id & tasklist id
        // on failure:
        // returns false
        $tasklist_name;
        $tasklist = [];
        $sql = "SELECT 
                tl.name AS 'tasklist_name',
                t.id AS 'task_id',
                t.task_name AS 'task_name', 
                t.task_status AS 'task_status',
                t.created_at AS 'created_at'
                FROM tasks AS t
                -- INNER JOIN tasklists AS tl ON t.list_id = tl.id
                -- INNER JOIN users AS u ON tl.user_id = u.id
                RIGHT JOIN tasklists AS tl ON t.list_id = tl.id
                RIGHT JOIN users AS u ON tl.user_id = u.id
                WHERE u.id = ? AND tl.id = ?
                ORDER BY t.created_at DESC;";
        if ($stmt = mysqli_prepare($this->conn, $sql)) {
            mysqli_stmt_bind_param($stmt, 'ii', $userId, $taskListId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $tasklist_name = array_shift($row);
                if ($row['task_id']) {
                    $tasklist[]= $row;
                }
            }
            return [
                'tasklist_name' => $tasklist_name,
                'tasklist' => $tasklist];
        } else {
            return false;
        }
    }

    public function deleteOneTaskList($userId, $taskListId)
    {
        // try to delete a tasklist based on user id & tasklist id
        // on success return 1
        // on failure return 0
        // if the tasklist not empty or not exists return -1
        $sql = "DELETE FROM tasklists 
                WHERE user_id = ? AND id = ?;";
        if ($stmt = mysqli_prepare($this->conn, $sql)) {
            mysqli_stmt_bind_param($stmt, 'ii', $userId, $taskListId);
            mysqli_stmt_execute($stmt);
            $result =  mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $result;
        } else {
            return false;
        }
    }

    public function updateOneTaskList($userId, $taskListId, $taskListName, $taskListList)
    {
        // STEP 1.: get & rename the tasklist based on user id & tasklist id
        //
        // code here -->>
        // $step_1;
        $sql = "UPDATE tasklists
                SET name = ?
                WHERE id = ? AND user_id = ?;";
        if ($stmt = mysqli_prepare($this->conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "sii", $taskListName, $taskListId, $userId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
        } else {
            return false;
        }
        //
        // STEP 2.: delete all record from tasks table based on tasklist id
        //
        // code here -->>
        $sql = "DELETE FROM tasks
                WHERE list_id = ?;";
        if ($stmt = mysqli_prepare($this->conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $taskListId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
        } else {
            return false;
        }
        //
        // STEP 3.: insert new record(s) into tasks table based on tasklist id
        //
        // code here -->>
        $sql = "INSERT INTO tasks (task_name, task_status, created_at, list_id) VALUES (?, ?, ?, ?);";
        if ($stmt = mysqli_prepare($this->conn, $sql)) {
            foreach ($taskListList as $list) {
                $name = $list['task_name'];
                $status = $list['task_status'];
                $created_at = $list['created_at'];
                $list_id = $taskListId;

                mysqli_stmt_bind_param($stmt, "siii", $name, $status, $created_at, $list_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_affected_rows($stmt);
                echo $result;
            }
            mysqli_stmt_close($stmt);
        } else {
            return false;
        }
        //
    }
}
