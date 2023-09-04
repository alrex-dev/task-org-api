<?php
namespace Controllers;

class Session {
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
        $projID = isset($_REQUEST['projID']) ? $_REQUEST['projID'] : '';

        $sql = sprintf("SELECT * FROM current_session WHERE proj_id = '%s' ORDER BY id DESC LIMIT 0, 1", $projID);
        
        $this->db->_setSQL($sql);
        $result = $this->db->_getQuerySingleResult();

        $output = array();

        if ($result) {
            $output = array(
                'id' => $result->id,
                'sessID' => $result->sess_id,
                'sessDate' => $result->sess_date,
                'sessTime' => $result->sess_time,
                'projID' => $result->proj_id
            );
        }

        echo json_encode($output);
    }

    public function post() {
        $data = json_decode( file_get_contents("php://input") );

        $sessID = isset($data->sessID) ? $data->sessID : '';
        $sessDate = isset($data->sessDate) ? $data->sessDate : '';
        $sessTime = isset($data->sessTime) ? $data->sessTime : '';
        $projID = isset($data->projID) ? $data->projID : '';

        if ($sessID && $sessDate && $sessTime && $projID) {
            $sql = sprintf("INSERT INTO current_session(sess_id, sess_date, sess_time, proj_id) VALUES ('%s', '%s', '%s', '%s')",
                $this->db->_escapeSQLString($sessID),
                $sessDate,
                $this->db->_escapeSQLString($sessTime),
                $projID
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

        $sessID = isset($data->sessID) ? $data->sessID : '';
        $sessDate = isset($data->sessDate) ? $data->sessDate : '';
        $sessTime = isset($data->sessTime) ? $data->sessTime : '';
        $projID = isset($data->projID) ? $data->projID : '';

        if ($sessID && $sessDate && $sessTime && $projID) {
            $sql = sprintf("UPDATE current_session SET sess_date='%s', sess_time='%s', proj_id='%s' WHERE sess_id='%s'",
                $sessDate,
                $this->db->_escapeSQLString($sessTime),
                $projID,
                $this->db->_escapeSQLString($sessID)
            );

            $this->db->_setSQL($sql);
            $result = $this->db->_executeSQL();

            $output = array('status' => $result);

            echo json_encode($output);
        }
    }

    public function delete() {
        $sessID = isset($_REQUEST['sessID']) ? $_REQUEST['sessID'] : '';

        if ($sessID) {
            $sql = sprintf("DELETE FROM current_session WHERE sess_id='%s'",
                $sessID
            );

            $this->db->_setSQL($sql);
            $result = $this->db->_executeSQL();

            $output = array('status' => $result);

            echo json_encode($output);
        }
    }
}
?>