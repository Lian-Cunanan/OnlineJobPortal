<?php
include '../../constants/db_config.php';
include '../constants/check-login.php';

// Retrieve and sanitize input data
$training = ucwords($_POST['training']);
$institution = ucwords($_POST['institution']);
$timeframe = ucwords($_POST['timeframe']);
$training_id = $_POST['trainingid'];

// Function to safely get file content
function getFileContent($file) {
    if ($file['error'] === UPLOAD_ERR_OK && $file['size'] > 0) {
        return file_get_contents($file['tmp_name']);
    }
    return '';
}

$certificate = isset($_FILES['certificate']) ? getFileContent($_FILES['certificate']) : '';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the base SQL query
    $query = "UPDATE tbl_training SET training = :training, institution = :institution, timeframe = :timeframe";

    // Add certificate to the query if provided and valid
    if ($certificate !== "") {
        if ($_FILES["certificate"]["size"] > 1000000) {
            header("location:../training.php?r=2290");
            exit();
        }
        $query .= ", certificate = :certificate";
    }

    $query .= " WHERE id = :trainid AND member_no = :member_no";

    $stmt = $conn->prepare($query);

    // Bind common parameters
    $stmt->bindParam(':training', $training);
    $stmt->bindParam(':institution', $institution);
    $stmt->bindParam(':timeframe', $timeframe);
    $stmt->bindParam(':trainid', $training_id);
    $stmt->bindParam(':member_no', $myid);

    // Bind certificate if provided
    if ($certificate !== "") {
        $stmt->bindParam(':certificate', $certificate, PDO::PARAM_LOB);
    }

    // Execute the query
    $stmt->execute();
    header("location:../training.php?r=5790");
} catch (PDOException $e) {
    // Log the error or handle it as needed
    error_log("Connection failed: " . $e->getMessage());
    echo "Connection failed: " . $e->getMessage();
}
?>
