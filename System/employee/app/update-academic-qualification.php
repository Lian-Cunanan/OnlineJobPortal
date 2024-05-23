<?php
require '../../constants/db_config.php';
require '../constants/check-login.php';

$id = $_POST['courseid'];
$country = $_POST['country'];
$course = ucwords($_POST['course']);
$institution = ucwords($_POST['institution']);
$timeframe = ucwords($_POST['timeframe']);
$level = $_POST['level'];

// Function to safely get file content
function getFileContent($file) {
    if ($file['error'] === UPLOAD_ERR_OK && $file['size'] > 0) {
        return file_get_contents($file['tmp_name']);
    }
    return '';
}

$certificate = isset($_FILES['certificate']) ? getFileContent($_FILES['certificate']) : '';
$transcript = isset($_FILES['transcript']) ? getFileContent($_FILES['transcript']) : '';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "UPDATE tbl_academic_qualification SET 
                country = :country, 
                institution = :institution, 
                course = :course, 
                level = :level, 
                timeframe = :timeframe";

    // Append certificate and transcript to the query if they are provided
    if ($certificate !== "") {
        if ($_FILES["certificate"]["size"] > 1000000) {
            header("location:../academic.php?r=2290");
            exit();
        }
        $query .= ", certificate = :certificate";
    }
    
    if ($transcript !== "") {
        if ($_FILES["transcript"]["size"] > 1000000) {
            header("location:../academic.php?r=2490");
            exit();
        }
        $query .= ", transcript = :transcript";
    }

    $query .= " WHERE id = :aid AND member_no = :member_no";

    $stmt = $conn->prepare($query);

    // Bind common parameters
    $stmt->bindParam(':country', $country);
    $stmt->bindParam(':institution', $institution);
    $stmt->bindParam(':course', $course);
    $stmt->bindParam(':level', $level);
    $stmt->bindParam(':timeframe', $timeframe);
    $stmt->bindParam(':aid', $id);
    $stmt->bindParam(':member_no', $myid);

    // Bind optional parameters if they are provided
    if ($certificate !== "") {
        $stmt->bindParam(':certificate', $certificate, PDO::PARAM_LOB);
    }
    if ($transcript !== "") {
        $stmt->bindParam(':transcript', $transcript, PDO::PARAM_LOB);
    }

    // Execute the query
    $stmt->execute();
    header("location:../academic.php?r=3214");
} catch (PDOException $e) {
    // Log the error or handle it as needed
    error_log("Error: " . $e->getMessage());
    echo "Error: " . $e->getMessage();
}
?>
