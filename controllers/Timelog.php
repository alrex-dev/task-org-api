<?php
namespace Controllers;

class Timelog {
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

        $sql = sprintf("SELECT id, log_id, log_date, time_from, time_to FROM timelogs 
            WHERE proj_id = '%s' ORDER BY log_date ASC, id ASC
        ", $projID);

        $this->db->_setSQL($sql);
        $results = $this->db->_getQueryResults();

        $output = array();

        if (count($results)) {
            $log_date_id = str_replace('-', '', $results[0]->log_date);
            $log_date = $results[0]->log_date;
            $logs = array();

            for($x=0; $x<count($results); $x++) {
                if ($log_date == $results[$x]->log_date) {
                    $logs[] = [
                        'log_id' => $results[$x]->id,
                        'log_ref_id' => $results[$x]->log_id,
                        'log_from' => $results[$x]->time_from,
                        'log_to' => $results[$x]->time_to,
                        'log_hrs' => 0
                    ];
                } else {
                    $output[] = [
                        'log_date_id' => $log_date_id,
                        'log_date' => $log_date,
                        'log_date_hrs' => 0,
                        'tlogs' => $logs
                    ];

                    $logs = array();

                    $logs[] = [
                        'log_id' => $results[$x]->id,
                        'log_ref_id' => $results[$x]->log_id,
                        'log_from' => $results[$x]->time_from,
                        'log_to' => $results[$x]->time_to,
                        'log_hrs' => 0
                    ];

                    $log_date_id = str_replace('-', '', $results[$x]->log_date);
                    $log_date = $results[$x]->log_date;
                }
            }

            if (count($logs)) {
                $output[] = [
                    'log_date_id' => $log_date_id,
                    'log_date' => $log_date,
                    'log_date_hrs' => 0,
                    'tlogs' => $logs
                ];
            }
        }

        echo json_encode($output);
    }

    public function post() {
        $data = json_decode( file_get_contents("php://input") );

        $logRefID = isset($data->logRefID) ? $data->logRefID : '';
        $logDate = isset($data->logDate) ? $data->logDate : '';
        $timeFrom = isset($data->timeFrom) ? $data->timeFrom : '';
        $timeTo = isset($data->timeTo) ? $data->timeTo : '';
        $projID = isset($data->projID) ? $data->projID : '';

        if ($logRefID && $logDate && $timeFrom && $timeTo && $projID) {
            $sql = sprintf("INSERT INTO timelogs(log_id, log_date, time_from, time_to, proj_id) VALUES ('%s', '%s', '%s', '%s', '%s')",
                $this->db->_escapeSQLString($logRefID),
                $logDate,
                $this->db->_escapeSQLString($timeFrom),
                $this->db->_escapeSQLString($timeTo),
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

        $itemID = isset($data->itemID) ? $data->itemID : 0;
        $timeFrom = isset($data->timeFrom) ? $data->timeFrom : '';
        $timeTo = isset($data->timeTo) ? $data->timeTo : '';

        if ($itemID && $timeFrom && $timeTo) {
            $sql = sprintf("UPDATE timelogs SET time_from='%s', time_to='%s' WHERE id=%d",
                $this->db->_escapeSQLString($timeFrom),
                $this->db->_escapeSQLString($timeTo),
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
            $sql = sprintf("DELETE FROM timelogs WHERE id=%d",
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