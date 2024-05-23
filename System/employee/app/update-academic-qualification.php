<?php
require '../../constants/db_config.php';
require '../constants/check-login.php';

$id = $_POST['courseid'];
$country  = $_POST['country'];
$course = ucwords($_POST['course']);
$institution = ucwords($_POST['institution']);
$timeframe = ucwords($_POST['timeframe']);
$level  = $_POST['level'];

// Initialize certificate and transcript
$certificate = '';
$transcript = '';

// Check if certificate file is uploaded
if (isset($_FILES['certificate']) && $_FILES['certificate']['error'] === UPLOAD_ERR_OK) {
    $certificate = addslashes(file_get_contents($_FILES['certificate']['tmp_name']));
}

// Check if transcript file is uploaded
if (isset($_FILES['transcript']) && $_FILES['transcript']['error'] === UPLOAD_ERR_OK) {
    $transcript = addslashes(file_get_contents($_FILES['transcript']['tmp_name']));
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Determine which SQL query to use based on the presence of certificate and transcript
    if ($certificate == "" && $transcript == "") {
        $stmt = $conn->prepare("UPDATE tbl_academic_qualification SET country = :country, institution = :institution, course = :course, level = :level, timeframe = :timeframe WHERE id= :aid AND member_no = :member_no");
    } elseif ($certificate !== "" && $transcript == "") {
        if ($_FILES["certificate"]["size"] > 1000000) {
            header("location:../academic.php?r=2290");
            exit();
        }
        $stmt = $conn->prepare("UPDATE tbl_academic_qualification SET country = :country, institution = :institution, course = :course, level = :level, timeframe = :timeframe, certificate = :certificate WHERE id= :aid AND member_no = :member_no");
        $stmt->bindParam(':certificate', $certificate);
    } elseif ($transcript !== "" && $certificate == "") {
        if ($_FILES["transcript"]["size"] > 1000000) {
            header("location:../academic.php?r=2490");
            exit();
        }
        $stmt = $conn->prepare("UPDATE tbl_academic_qualification SET country = :country, institution = :institution, course = :course, level = :level, timeframe = :timeframe, transcript = :transcript WHERE id= :aid AND member_no = :member_no");
        $stmt->bindParam(':transcript', $transcript);
    } elseif ($transcript !== "" && $certificate !== "") {
        if ($_FILES["certificate"]["size"] > 1000000) {
            header("location:../academic.php?r=2290");
            exit();
        }
        if ($_FILES["transcript"]["size"] > 1000000) {
            header("location:../academic.php?r=2490");
            exit();
        }
        $stmt = $conn->prepare("UPDATE tbl_academic_qualification SET country = :country, institution = :institution, course = :course, level = :level, timeframe = :timeframe, certificate = :certificate, transcript = :transcript WHERE id= :aid AND member_no = :member_no");
        $stmt->bindParam(':certificate', $certificate);
        $stmt->bindParam(':transcript', $transcript);
    }

    // Bind common parameters
    $stmt->bindParam(':country', $country);
    $stmt->bindParam(':institution', $institution);
    $stmt->bindParam(':course', $course);
    $stmt->bindParam(':level', $level);
    $stmt->bindParam(':timeframe', $timeframe);
    $stmt->bindParam(':aid', $id);
    $stmt->bindParam(':member_no', $myid);
    
    // Execute the query
    $stmt->execute();
    header("location:../academic.php?r=3214");
} catch (PDOException $e) {
    // Log the error or handle it as needed
    echo "Error: " . $e->getMessage();
}
?>
