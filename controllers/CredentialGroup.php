<?php
namespace Controllers;

class CredentialGroup {
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
        $output = array('status' => true);

        echo json_encode($output);
    }

    public function post() {
        $data = json_decode( file_get_contents("php://input") );

        $projID = isset($data->projID) ? $data->projID : '';
        $groupName = isset($data->groupName) ? $data->groupName : 0;
        $noteLabel = isset($data->noteLabel) ? $data->noteLabel : '';
        $noteValue = isset($data->noteValue) ? $data->noteValue : '';

        if ($projID && $groupName) {
            $sql = sprintf("INSERT INTO access_note_groups(group_name, proj_id) VALUES ('%s', '%s')",
                $this->db->_escapeSQLString($groupName),
                $this->db->_escapeSQLString($projID),
            );

            $this->db->_setSQL($sql);
            $result = $this->db->_executeSQL();
            $gid = $this->db->last_insert_id;

            $id = 0;

            if ($result && $noteLabel && $noteValue) {
                $sql = sprintf("INSERT INTO access_notes(note_label, note_value, group_id) VALUES ('%s', '%s', %d)",
                    $this->db->_escapeSQLString($noteLabel),
                    $this->db->_escapeSQLString($noteValue),
                    $gid
                );

                $this->db->_setSQL($sql);
                $result2 = $this->db->_executeSQL();
                $id = $this->db->last_insert_id;
            }

            $output = array('status' => $result, 'gid' => $gid, 'id' => $id);

            echo json_encode($output);
        }
        
    }

    public function put() {
        $data = json_decode( file_get_contents("php://input") );

        $groupID = isset($data->groupID) ? $data->groupID : 0;
        $groupName = isset($data->groupName) ? $data->groupName : '';

        if ($groupID && $groupName) {
            $sql = sprintf("UPDATE access_note_groups SET group_name='%s' WHERE id=%d",
                $this->db->_escapeSQLString($groupName),
                $groupID
            );

            $this->db->_setSQL($sql);
            $result = $this->db->_executeSQL();

            $output = array('status' => $result);

            echo json_encode($output);
        }
    }

    public function delete() {
        $groupID = isset($_REQUEST['groupID']) ? $_REQUEST['groupID'] : '';

        if ($groupID) {
            $sql = sprintf("DELETE FROM access_note_groups WHERE id=%d",
                $groupID
            );

            $this->db->_setSQL($sql);
            $result = $this->db->_executeSQL();

            $sql = sprintf("DELETE FROM access_notes WHERE group_id=%d",
                $groupID
            );

            $this->db->_setSQL($sql);
            $result2 = $this->db->_executeSQL();

            $output = array('status' => $result);

            echo json_encode($output);
        }
    }
}
?>