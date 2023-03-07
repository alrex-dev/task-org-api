<?php
namespace Controllers;

class NodeTask {
    public $db = null;
    public $func = '';

    public function __construct($request, $db) {
        $this->db = $db;
        $this->func = $request;
    }

    public function process() {
        call_user_func(array($this, $this->func));
    }

    public function get() {
        $sql = sprintf("SELECT * FROM node_task_pool ORDER BY task_id ASC");

        $this->db->_setSQL($sql);
        $results = $this->db->_getQueryResults();

        $output = array('tasks' => []);

        if (count($results)) {
            $tasks = array();
            $task_ids = array();

            for($x=0; $x<count($results); $x++) {
                $data = explode(',', $results[$x]->task_data);
                $task_data = array();

                for($y=0; $y<count($data); $y++) {
                    $d = explode(':', $data[$y]);
                    $task_data[ $d[0] ] = $d[1];
                }

                $tasks[] = array('task' => $results[$x]->task, 'taskData' => $task_data);

                $task_ids[] = $results[$x]->task_id;
            }

            $sql = sprintf("DELETE FROM node_task_pool WHERE task_id in (%s)",
                implode(',', $task_ids)
            );

            $this->db->_setSQL($sql);
            $result = $this->db->_executeSQL();
            $output['tasks'] = $tasks;
        }

        echo json_encode($output);
    }

    public function post() {
        $data = json_decode( file_get_contents("php://input") );

        $task = isset($data->task) ? $data->task : '';
        $taskData = isset($data->taskData) ? $data->taskData : '';

        if ($task && $taskData) {
            $sql = sprintf("INSERT INTO node_task_pool(task, task_data) VALUES ('%s', '%s')",
                $this->db->_escapeSQLString($task),
                $this->db->_escapeSQLString($taskData)
            );

            $this->db->_setSQL($sql);
            $result = $this->db->_executeSQL();
            $id = $this->db->last_insert_id;

            $output = array('status' => $result, 'id' => $id);

            echo json_encode($output);
        }
        
    }

    public function put() {
        $data = json_decode( file_get_contents("php://input") );

        $itemID = isset($data->itemID) ? $data->itemID : 0;
        $actDesc = isset($data->actDesc) ? $data->actDesc : '';

        $output = array();

        echo json_encode($output);
    }

    public function delete() {
        $output = array();

        echo json_encode($output);
    }
}
?>