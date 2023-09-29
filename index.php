<?php
require_once('autoloader.php');

use Library\Database;

$params = new stdClass();

$params->host 		= 'localhost';
$params->username 	= 'root';
$params->password 	= 'pablopicasso';
$params->db 		= 'task_org';

$db = new Database( $params );

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE, PATCH');
header('Access-Control-Allow-Headers: token, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
//header('Access-Control-Allow-Headers: token, Content-Type');
header("Access-Control-Max-Age: 3600");
//header('Access-Control-Max-Age: 1728000');
header('Content-Type: application/json; charset=UTF-8');

$requestMethod = strtolower($_SERVER["REQUEST_METHOD"]);

$entity = isset($_REQUEST['entity']) ? $_REQUEST['entity'] : '';

use Controllers\Project;
use Controllers\Credential;
use Controllers\CredentialGroup;
use Controllers\Timelog;
use Controllers\Activity;
use Controllers\Session;
use Controllers\RecentActivities;
use Controllers\NodeTask;

$entityObj = null;

switch($entity) {
    case 'credential':
        $entityObj = new Credential($requestMethod, $db);
    break;
    case 'credential-group':
        $entityObj = new CredentialGroup($requestMethod, $db);
    break;

    case 'timelog':
        $entityObj = new Timelog($requestMethod, $db);
    break;

    case 'activity':
        $entityObj = new Activity($requestMethod, $db);
    break;

    case 'project':
        $entityObj = new Project($requestMethod, $db);
    break;

    case 'session':
        $entityObj = new Session($requestMethod, $db);
    break;

    case 'recent-activities':
        $entityObj = new RecentActivities($requestMethod, $db);
    break;

    case 'nodetask':
        $entityObj = new NodeTask($requestMethod, $db);
    break;

    default:
    break;
}

if ($entityObj) {
    $entityObj->process();
}
?>