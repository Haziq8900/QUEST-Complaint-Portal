<?php

session_start();

include 'config/database.php';

$full_name = $_POST['full-name'];
$email = $_POST['university-email'];
$department = $_POST['department'];
$roll_number = $_POST['roll-number'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm-password'];

if ($password != $confirm_password) {

    echo "Passwords do not match.";
    exit();
}

$email_check = "SELECT * FROM students
                WHERE university_email='$email'";

$email_result = $conn->query($email_check);

if ($email_result->num_rows > 0) {

    echo "University email is already registered.";
    exit();
}

$roll_check = "SELECT * FROM students
                WHERE roll_number='$roll_number'";

$roll_result = $conn->query($roll_check);

if ($roll_result->num_rows > 0) {

    echo "Roll number is already registered.";
    exit();
}

$query = "INSERT INTO students
(full_name,university_email,password,department,roll_number)
VALUES
('$full_name','$email','$password','$department','$roll_number')";

$result = $conn->query($query);

if ($result) {

    header("Location: login.php");
    exit();
} else {

    echo "Registration failed.";
}

$conn->close();
