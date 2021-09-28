<?php
include 'UserAuth.php';

class Lists extends UserAuth
{
    public function createNewTasklist($userId, $tasklistName, $taskList = '')
    {
        $result;
        // create a query stirng
        $sql = "INSERT INTO lists (user_id, task_list_name, task_list)
                VALUES (?, ?, ?);";

        // prepared statement
        $stmt = mysqli_prepare($this->conn, $sql);

        // bind parameters for markers
        mysqli_stmt_bind_param($stmt, 'iss', $userId, $tasklistName, $taskList);

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
        $taslListCollection = [];
        $sql = "SELECT task_list_id, task_list_name, task_list FROM lists WHERE user_id = ?;";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            // get the tasklist
            $tasklist = json_decode($row['task_list'], true);

            // count all tasks in the tasklist
            $countAll = count($tasklist);

            // filter only true (done) tasks from tasklist
            $tasklistTrue = array_filter($tasklist, function ($task) {
                return $task['status'] === true;
            });

            // count only true (done) tasks
            $countTrue = count($tasklistTrue);

            // make the result assoc array
            $data = [
                'task_list_id' => $row['task_list_id'],
                'task_list_name' => $row['task_list_name'],
                'count_all' => $countAll,
                'count_true' => $countTrue
            ];

            // push into the return data
            array_push($taslListCollection, $data);
        }
        mysqli_stmt_close($stmt);
        return $taslListCollection;
    }

    public function getOneTaskList($userId, $taskListId)
    {
        $sql = "SELECT task_list_name, task_list
              FROM lists
              WHERE user_id = ? AND task_list_id = ?;";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $userId, $taskListId);
        mysqli_stmt_execute($stmt);
        if ($result = mysqli_stmt_get_result($stmt)) {
            $data = mysqli_fetch_assoc($result);

            $data['task_list'] = json_decode($data['task_list'], true);

            return $data;
        } else {
            return false;
        }
    }

    public function deleteOneTaskList($userId, $taskListId)
    {
        $sql = "DELETE FROM lists WHERE user_id = ? AND task_list_id = ?;";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $userId, $taskListId);
        mysqli_stmt_execute($stmt);
        $affectedRows = mysqli_stmt_affected_rows($stmt);
        if ($affectedRows) {
            return true;
        } else {
            return false;
        }
        mysqli_stmt_close($stmt);
    }

    public function updateOneTaskList($userId, $taskListId, $taskListName, $taskListList)
    {
        $taskListList = json_encode($taskListList);
        $sql = "UPDATE lists
                SET task_list_name = ? , task_list = ?
                WHERE user_id = ? AND task_list_id = ?;";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssii', $taskListName, $taskListList, $userId, $taskListId);
        mysqli_stmt_execute($stmt);
        $affectedRows = mysqli_stmt_affected_rows($stmt);
        if ($affectedRows) {
            return true;
        } else {
            return false;
        }
        mysqli_stmt_close($stmt);
    }
}
