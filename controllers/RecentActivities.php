<?php
namespace Controllers;

class RecentActivities {
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
        $start_date = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';

        $filter = ($start_date) ? " WHERE date_ < '".$start_date."'" : '';

        //Get unique dates
        $sql = sprintf("SELECT DISTINCT date_ FROM (
                SELECT DISTINCT log_date AS date_ FROM timelogs 
                UNION ALL
                SELECT DISTINCT act_date as date_ FROM activities
            ) AS main %s ORDER BY date_ DESC LIMIT 0, 7"
            , $filter
        );

        $this->db->_setSQL($sql);
        $results = $this->db->_getQueryResults();

        $dates = array();
        for($x=0; $x<count($results); $x++) {
            $dates[] = $results[$x]->date_;
        }

        //Get activities
        $sql = sprintf("SELECT a.id, a.act_date, a.act_ref_log, a.act_desc, b.proj_name 
                FROM activities a LEFT JOIN projects b ON a.proj_id = b.proj_id 
                WHERE a.act_date IN ('%s') ORDER BY a.act_date DESC"
            , implode("','", $dates)
        );

        $this->db->_setSQL($sql);
        $results = $this->db->_getQueryResults();

        $activities = array();
        $cur_date = '';
        for($x=0; $x<count($results); $x++) {
            if ($cur_date != $results[$x]->act_date) {
                $cur_date = $results[$x]->act_date;
                $activities[$cur_date] = array();
            }

            $activities[$cur_date][] = $results[$x];
        }

        //Preparing final data
        $noact_id = 1;
        $data = array();
        for($x=0; $x<count($dates); $x++) {
            if (isset($activities[ $dates[$x] ])) {
                $d = $activities[ $dates[$x] ];

                for($y=0; $y<count($d); $y++) {
                    $data[] = array(
                        'id' => $d[$y]->id,
                        'act_date' => $d[$y]->act_date,
                        'act_desc' => $d[$y]->act_desc,
                        'proj_name' => $d[$y]->proj_name
                    );
                }
            } else {
                $sql = sprintf("SELECT a.log_date, b.proj_name 
                        FROM timelogs a LEFT JOIN projects b ON a.proj_id = b.proj_id 
                        WHERE a.log_date = '%s'"
                    , $dates[$x]
                );

                $this->db->_setSQL($sql);
                $results = $this->db->_getQueryResults();
                
                for($y=0; $y<count($results); $y++) {
                    $data[] = array(
                        'id' => 't'.$noact_id,
                        'act_date' => $results[$y]->log_date,
                        'act_desc' => 'No Activities',
                        'proj_name' => $results[$y]->proj_name
                    );

                    $noact_id++;
                }
            }
        }

        $lastDate = isset($dates[$x-1]) ? $dates[$x-1] : '';

        //Get the remaining count
        $sql = sprintf("SELECT COUNT(date_) as dates_count FROM (
                SELECT DISTINCT log_date AS date_ FROM timelogs 
                UNION ALL
                SELECT DISTINCT act_date as date_ FROM activities
            ) AS main WHERE date_ < '%s'"
            , $lastDate
        );

        $this->db->_setSQL($sql);
        $result = $this->db->_getQuerySingleResult();

        echo json_encode(array("remaining" => $result->dates_count, "data" => $data, "last_date" => $lastDate));
    }
}
?>