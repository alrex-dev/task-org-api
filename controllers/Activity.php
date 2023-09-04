<?php
namespace Controllers;

class Activity {
    public $db = null;
    public $func = '';

    public function __construct($request, $db) {
        $this->db = $db;
        $this->func = $request;
    }

    public function process() {
        call_user_func(array($this, $this->func));
    }

    //$date     format(mm/dd/yyy)
    public function prepareDate($date) {
        $d = '';

        if ($date) {
            //$dArr = explode('/', $date);

            //$d = $dArr[2].'-'.$dArr[0].'-'.$dArr[1];

            $d = date('Y-m-d', strtotime($date));
        }

        return $d;
    }

    public function get() {
        $projID = isset($_REQUEST['projID']) ? $_REQUEST['projID'] : '';
        $keyword = isset($_REQUEST['kw']) ? $_REQUEST['kw'] : '';
        $dateFrom = isset($_REQUEST['dateFrom']) ? $_REQUEST['dateFrom'] : '';
        $dateTo = isset($_REQUEST['dateTo']) ? $_REQUEST['dateTo'] : '';
        
        //echo $dateFrom;

        $dateFrom = $this->prepareDate($dateFrom);
        $dateTo = $this->prepareDate($dateTo);

        $filter = ' ';

        if ($keyword) {
            $filter .= sprintf("AND act_desc LIKE '%%%s%%'", $this->db->_escapeSQLString($keyword));
        }

        if ($dateFrom || $dateTo) {
            if ($dateFrom && $dateTo == '') {
                $filter .= sprintf("AND act_date >= '%s'", $dateFrom);
            } else if ($dateFrom == '' && $dateTo) {
                $filter .= sprintf("AND act_date <= '%s'", $dateTo);
            } else {
                $filter .= sprintf("AND (act_date >= '%s' AND act_date <= '%s')", $dateFrom, $dateTo);
            }
        }

        $sql = sprintf("SELECT id, act_date, act_ref_log, act_desc FROM activities 
            WHERE proj_id = '%s' %s ORDER BY act_date ASC, id ASC
        ", $projID, $filter);

        //echo $sql;

        $this->db->_setSQL($sql);
        $results = $this->db->_getQueryResults();

        $output = array();

        if (count($results)) {
            $act_date_id = str_replace('-', '', $results[0]->act_date);
            $act_date = $results[0]->act_date;
            $actions = array();

            for($x=0; $x<count($results); $x++) {
                if ($act_date == $results[$x]->act_date) {
                    $actions[] = [
                        'action_id' => $results[$x]->id,
                        'action_desc' => $results[$x]->act_desc,
                        'timelog_id' => $results[$x]->act_ref_log,
                        'log_from' => '',
                        'log_to' => ''
                    ];
                } else {
                    $output[] = [
                        'act_date_id' => $act_date_id,
                        'act_date' => $act_date,
                        'actions' => $actions
                    ];

                    $actions = array();

                    $actions[] = [
                        'action_id' => $results[$x]->id,
                        'action_desc' => $results[$x]->act_desc,
                        'timelog_id' => $results[$x]->act_ref_log,
                        'log_from' => '',
                        'log_to' => ''
                    ];

                    $act_date_id = str_replace('-', '', $results[$x]->act_date);
                    $act_date = $results[$x]->act_date;
                }
            }

            if (count($actions)) {
                $output[] = [
                    'act_date_id' => $act_date_id,
                    'act_date' => $act_date,
                    'actions' => $actions
                ];
            }
        }

        echo json_encode($output);
    }

    public function post() {
        $data = json_decode( file_get_contents("php://input") );

        $actDate = isset($data->actDate) ? $data->actDate : '';
        $actDesc = isset($data->actDesc) ? $data->actDesc : '';
        $timeRefID = isset($data->timeRefID) ? $data->timeRefID : '';
        $projID = isset($data->projID) ? $data->projID : '';

        if ($actDate && $actDesc && $projID) {
            $sql = sprintf("INSERT INTO activities(act_date, act_ref_log, act_desc, proj_id) VALUES ('%s', '%s', '%s', '%s')",
                $actDate,
                $this->db->_escapeSQLString($timeRefID),
                $this->db->_escapeSQLString($actDesc),
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
        $actDesc = isset($data->actDesc) ? $data->actDesc : '';

        if ($itemID && $actDesc) {
            $sql = sprintf("UPDATE activities SET act_desc='%s' WHERE id=%d",
                $this->db->_escapeSQLString($actDesc),
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
            $sql = sprintf("DELETE FROM activities WHERE id=%d",
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