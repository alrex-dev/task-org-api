<?php
namespace Controllers;

class Project {
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
        $search = isset($_REQUEST['search']) ? (boolean)$_REQUEST['search'] : false;

        if ($search) {
            $kw = isset($_REQUEST['kw']) ? $_REQUEST['kw'] : '';

            $sql = sprintf("SELECT id, proj_id, proj_name FROM projects WHERE proj_name LIKE '%%%s%%'", 
                $this->db->_escapeSQLString($kw)
            );

            $this->db->_setSQL($sql);
            $results = $this->db->_getQueryResults();

            $output = count($results) ? $results : array();
        } else {
            $sql = sprintf("SELECT id, proj_id, proj_name, proj_desc, proj_storage FROM projects WHERE proj_id = '%s'", $projID);

            $this->db->_setSQL($sql);
            $result = $this->db->_getQuerySingleResult();

            $output = array();

            if ($result) {
                $output = array(
                    'id' => $result->id,
                    'projID' => $result->proj_id,
                    'projName' => $result->proj_name,
                    'projDesc' => $result->proj_desc,
                    'projStorage' => $result->proj_storage
                );
            }
        }

        echo json_encode($output);
    }

    public function post() {
        $data = json_decode( file_get_contents("php://input") );

        $projID = strtolower(str_replace(' ', '_', $data->projName));
        $projID .= '_'.date('YmdHms');

        $projID = md5($projID);
        $projName = isset($data->projName) ? $data->projName : '';
        $projDesc = isset($data->projDesc) ? $data->projDesc : '';

        $prodStorage = strtolower( preg_replace("/[^A-Za-z0-9 ]/", '', $projName) );
        $prodStorage = 'p_'.str_replace(' ', '_', $prodStorage).'_'.date('YmdHis');

        if ($projID && $projName) {
            $sql = sprintf("INSERT INTO projects(proj_id, proj_name, proj_desc, proj_storage) VALUES ('%s', '%s', '%s', '%s')",
                $this->db->_escapeSQLString($projID),
                $this->db->_escapeSQLString($projName),
                $this->db->_escapeSQLString($projDesc),
                $this->db->_escapeSQLString($prodStorage)
            );

            $this->db->_setSQL($sql);
            $result = $this->db->_executeSQL();
            $id = $this->db->last_insert_id;

            $output = array('status' => $result, 'id' => $id, 'projID' => $projID, 'projStorage' => $prodStorage);

            echo json_encode($output);
        }
    }

    public function put() {
        $data = json_decode( file_get_contents("php://input") );

        $id = isset($data->id) ? $data->id : 0;
        $projName = isset($data->projName) ? $data->projName : '';
        $projDesc = isset($data->projDesc) ? $data->projDesc : '';

        if ($id && $projName) {
            $sql = sprintf("UPDATE projects SET proj_name='%s', proj_desc='%s' WHERE id=%d",
                $this->db->_escapeSQLString($projName),
                $this->db->_escapeSQLString($projDesc),
                $id
            );

            $this->db->_setSQL($sql);
            $result = $this->db->_executeSQL();

            $output = array('status' => $result);

            echo json_encode($output);
        }
    }

    public function delete() {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
        $projID = isset($_REQUEST['projID']) ? $_REQUEST['projID'] : '';

        if ($id) {
            //Project
            $sql = sprintf("DELETE FROM projects WHERE id=%d", $id);

            $this->db->_setSQL($sql);
            $result = $this->db->_executeSQL();

            //Access Notes
            $sql = sprintf("DELETE FROM access_notes WHERE group_id IN (SELECT id FROM access_note_groups WHERE proj_id='%s')", $projID);

            $this->db->_setSQL($sql);
            $result2 = $this->db->_executeSQL();

            //Access Note Groups
            $sql = sprintf("DELETE FROM access_note_groups WHERE proj_id='%s'", $projID);

            $this->db->_setSQL($sql);
            $result3 = $this->db->_executeSQL();

            //Timelogs
            $sql = sprintf("DELETE FROM timelogs WHERE proj_id='%s'", $projID);

            $this->db->_setSQL($sql);
            $result4 = $this->db->_executeSQL();

            //Activities
            $sql = sprintf("DELETE FROM activities WHERE proj_id='%s'", $projID);

            $this->db->_setSQL($sql);
            $result5 = $this->db->_executeSQL();


            $output = array('status' => ($result && $result2 && $result3 && $result4 && $result5));

            echo json_encode($output);
        }
    }
}
?>