<?php
namespace Controllers;

class Credential {
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

        $sql = sprintf("SELECT a.id, a.group_name, b.id AS nid, b.note_label, b.note_value 
            FROM access_note_groups a RIGHT JOIN access_notes b ON a.id = b.group_id 
            WHERE a.proj_id = '%s' ORDER BY a.id ASC, b.id ASC
        ", $projID);

        $this->db->_setSQL($sql);
        $results = $this->db->_getQueryResults();

        $output = array();

        if (count($results)) {
            $gid = $results[0]->id;
            $gname = $results[0]->group_name;
            $notes = array();

            for($x=0; $x<count($results); $x++) {
                if ($gid == $results[$x]->id) {
                    $notes[] = [
                        'c_item_id' => $results[$x]->nid,
                        'c_item_label' => $results[$x]->note_label,
                        'c_item_value' => $results[$x]->note_value
                    ];
                } else {
                    $output[] = [
                        'c_group_id' => $gid,
                        'c_group_name' => $gname,
                        'items' => $notes
                    ];

                    $notes = array();

                    $notes[] = [
                        'c_item_id' => $results[$x]->nid,
                        'c_item_label' => $results[$x]->note_label,
                        'c_item_value' => $results[$x]->note_value
                    ];

                    $gid = $results[$x]->id;
                    $gname = $results[$x]->group_name;
                }
            }

            if (count($notes)) {
                $output[] = [
                    'c_group_id' => $gid,
                    'c_group_name' => $gname,
                    'items' => $notes
                ];
            }
        }

        echo json_encode($output);
    }

    public function post() {
        $data = json_decode( file_get_contents("php://input") );

        $groupID = isset($data->groupID) ? $data->groupID : 0;
        $noteLabel = isset($data->noteLabel) ? $data->noteLabel : '';
        $noteValue = isset($data->noteValue) ? $data->noteValue : '';

        if ($groupID && $noteLabel && $noteValue) {
            $sql = sprintf("INSERT INTO access_notes(note_label, note_value, group_id) VALUES ('%s', '%s', %d)",
                $this->db->_escapeSQLString($noteLabel),
                $this->db->_escapeSQLString($noteValue),
                $groupID
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
        $noteLabel = isset($data->noteLabel) ? $data->noteLabel : '';
        $noteValue = isset($data->noteValue) ? $data->noteValue : '';

        if ($itemID && $noteLabel && $noteValue) {
            $sql = sprintf("UPDATE access_notes SET note_label='%s', note_value='%s' WHERE id=%d",
                $this->db->_escapeSQLString($noteLabel),
                $this->db->_escapeSQLString($noteValue),
                $itemID
            );

            $this->db->_setSQL($sql);
            $result = $this->db->_executeSQL();

            $output = array('status' => $result);

            echo json_encode($output);
        }
    }

    public function delete() {
        $itemID = isset($_REQUEST['itemID']) ? $_REQUEST['itemID'] : '';

        if ($itemID) {
            $sql = sprintf("DELETE FROM access_notes WHERE id=%d",
                $itemID
            );

            $this->db->_setSQL($sql);
            $result = $this->db->_executeSQL();

            $output = array('status' => $result);

            echo json_encode($output);
        }
    }
}
?>