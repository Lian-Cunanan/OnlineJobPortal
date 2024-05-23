<?php
require '../../constants/db_config.php';
require '../constants/check-login.php';

$id = $_POST['courseid']; // Assuming you're passing the course ID via POST

$country = $_POST['country'];
$course = ucwords($_POST['course']);
$institution = ucwords($_POST['institution']);
$timeframe = ucwords($_POST['timeframe']);

// Function to safely get file content
function getFileContent($file) {
    if ($file['error'] === UPLOAD_ERR_OK && $file['size'] > 0) {
        return file_get_contents($file['tmp_name']);
    }
    return '';
}

$certificate = isset($_FILES['certificate']) ? getFileContent($_FILES['certificate']) : '';

if (empty($certificate)) {
    header("location:../qualifications.php?r=certificate_required");
    exit();
}

if ($_FILES["certificate"]["size"] > 1000000) {
    header("location:../qualifications.php?r=certificate_size_limit_exceeded");
    exit();
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the record exists before updating
    $checkStmt = $conn->prepare("SELECT * FROM tbl_professional_qualification WHERE id = :id AND member_no = :memberno");
    $checkStmt->bindParam(':id', $id);
    $checkStmt->bindParam(':memberno', $myid);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) { // If record exists, update it
        $updateStmt = $conn->prepare("UPDATE tbl_professional_qualification SET country = :country, institution = :institution, title = :title, timeframe = :timeframe, certificate = :certificate WHERE id = :id AND member_no = :memberno");
        $updateStmt->bindParam(':country', $country);
        $updateStmt->bindParam(':institution', $institution);
        $updateStmt->bindParam(':title', $course);
        $updateStmt->bindParam(':timeframe', $timeframe);
        $updateStmt->bindParam(':certificate', $certificate, PDO::PARAM_LOB);
        $updateStmt->bindParam(':id', $id);
        $updateStmt->bindParam(':memberno', $myid);
        $updateStmt->execute();

        header("location:../qualifications.php?r=update_success");
        exit();
    } else { // Record does not exist
        header("location:../qualifications.php?r=record_not_found");
        exit();
    }
} catch(PDOException $e) {
    // Log the error or handle it as needed
    error_log("Error: " . $e->getMessage());
    header("location:../qualifications.php?r=database_error");
    exit();
}
?>
