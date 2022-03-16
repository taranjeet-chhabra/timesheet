<?php
require_once('config.php');
require_once('db.php');
$db = new db($config['database']['host'], $config['database']['db_user'], $config['database']['db_password'], $config['database']['db_name']);
$date = date('Y-m-d', strtotime($_POST['report_date']));
$reportDate = date('d/m/Y', strtotime($_POST['report_date']));
$toEmail = $_POST['email_to'];
$subject = $_POST['subject'];
$reportedTasks = array();
for ($i = 0; $i < count($_POST['projects']); $i++) {
    $reportedTasks[$i]['project'] = $_POST['projects'][$i];
    $reportedTasks[$i]['task'] = $_POST['task'][$i];
    $reportedTasks[$i]['description'] = $_POST['description'][$i];
    $reportedTasks[$i]['hour'] = $_POST['hours'][$i];
    $reportedTasks[$i]['minute'] = $_POST['minutes'][$i];
    $reportedTasks[$i]['billable'] = (isset($_POST['billable'][$i]) && $_POST['billable'][$i]=='on') ? 1 : 0;
}

$reportQuery = "INSERT INTO timesheet(report_date, email_to, report_subject) VALUES(?, ?, ?)";

$db->query($reportQuery, $date, $toEmail, $subject);

$reportId = $db->lastInsertID();
foreach ($reportedTasks as $task) {
    $taskTime = $task['hour'].":".$task['minute'];
    $taskQuery = "INSERT INTO timesheet_tasks(timesheet_id, project, task, task_description, task_time, billable) VALUES(?, ?, ?, ?, ?, ?)";    
    $db->query($taskQuery, $reportId, $task['project'], $task['task'], $task['description'], $taskTime, $task['billable']);
}

$message = "<html><head></head><body><table><thead><tr><th>Report Date : </th><th>".$reportDate."</th></tr></thead></table>";
$message .= "<table border='1'><thead><tr><th>S. No.</th><th>Project</th><th>Task</th><th>Description</th><th>Time Worked</th><th>Billable</th></tr></thead><tbody>";
$counter = 1;
foreach ($reportedTasks as $task) {
    $taskTime = (($task['hour'] < 10)? "0".$task['hour']: $task['hour']).":".$task['minute'];
    $message .= "<tr><td align='center'>".$counter."</td><td  align='center'>".$task['project']."</td><td align='center'>".$task['task']."</td><td align='center'>".$task['description']."</td><td align='center'>".$taskTime."</td><td align='center'>".(($task['billable'] == 1) ? "Yes" : "No")."</td></tr>";
    $counter++;
}
$message .= "</tbody></table></body></html>";

$header = "From:"+$email+" \r\n";
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-type: text/html\r\n";
$retval = mail ($toEmail,$subject,$message,$header);

if( $retval == true ) {
    header("Location:".$config['baseUrl']."/index.php?sent=1");
 }else {
    header("Location:".$config['baseUrl']."/index.php?sent=0");
 }