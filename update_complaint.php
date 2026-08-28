<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include 'config/database.php';

if (!isset($_POST['complaint_id']) || !isset($_POST['status']) || !isset($_POST['response'])) {
    header("Location: admin_complaints.php");
    exit();
}

$complaint_id = $conn->real_escape_string($_POST['complaint_id']);
$status = $conn->real_escape_string($_POST['status']);
$response = $conn->real_escape_string($_POST['response']);

$query = "UPDATE complaints 
            SET status='$status',
            admin_response='$response',
            updated_at=CURRENT_TIMESTAMP
            WHERE complaint_id='$complaint_id'";

$result = $conn->query($query);

if ($result) {
    header("Location: admin_complaint_details.php?id=$complaint_id");
    exit();
} else {
    echo "Failed to update complaint.";
}

$conn->close();
