<?php
require '../constants/settings.php';
if (isset($default_timezone) && !empty($default_timezone)) {
    if (date_default_timezone_set($default_timezone) === false) {
        // If setting the provided timezone fails, fallback to a default timezone
        date_default_timezone_set('UTC');
        error_log("Failed to set timezone to '$default_timezone'. Falling back to 'UTC'.");
    }
} else {
    // Handle the case where $default_timezone is not set or is empty
    date_default_timezone_set('UTC'); // Default to UTC if not set
    error_log("Timezone was not set or is empty. Defaulting to 'UTC'.");
}
$apply_date = date('m/d/Y');

session_start();
if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
$myid = $_SESSION['myid'];	
$myrole = $_SESSION['role'];
$opt = $_GET['opt'];

if ($myrole == "employee"){
include '../constants/db_config.php';

    try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	
    $stmt = $conn->prepare("SELECT * FROM tbl_job_applications WHERE member_no = '$myid' AND job_id = :jobid");
	$stmt->bindParam(':jobid', $opt);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $rec = count($result);
	
	if ($rec == 0) {
	
    try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	
    $stmt = $conn->prepare("INSERT INTO tbl_job_applications (member_no, job_id, application_date)
    VALUES (:memberno, :jobid, :appdate)");
    $stmt->bindParam(':memberno', $myid);
    $stmt->bindParam(':jobid', $opt);
    $stmt->bindParam(':appdate', $apply_date);
    $stmt->execute();
	
	print '<br>
	 <div class="alert alert-success">
     You have successfully applied this job.
	 </div>
     ';
					  
	}catch(PDOException $e)
    {

    }
	
		
	}else{
	foreach($result as $row)
    {
	 print '<br>
	 <div class="alert alert-warning">
     You have already applied this job before , you can not apply again.
	 </div>
     ';
	}	
		
	}


					  
	}catch(PDOException $e)
    {

    }
	
}}

?>