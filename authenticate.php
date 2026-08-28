<?php

session_start();

include 'config/database.php';

$account_type = $_POST['account-type'];
$email = $_POST['university-email'];
$password = $_POST['password'];

if ($account_type == "student") {
    $sql = "SELECT * FROM students 
            WHERE university_email='$email'
            AND password='$password'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();

        $_SESSION['student_id'] = $student['student_id'];
        $_SESSION['student_name'] = $student['full_name'];
        $_SESSION['student_email'] = $student['university_email'];
        $_SESSION['student_department'] = $student['department'];
        $_SESSION['student_roll_no'] = $student['roll_no'];

        header("Location: student_dashboard.php");
        exit();

    } else {
        echo "Invalid student email or password.";
    }

}

else if ($account_type == "admin") {

    $sql = "SELECT * FROM admins
            WHERE university_email='$email'
            AND password='$password'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['full_name'];
        $_SESSION['admin_email'] = $admin['university_email'];
        $_SESSION['admin_department'] = $admin['department'];

        header("Location: admin_dashboard.php");
        exit();

    } else {
        echo "Invalid admin email or password.";
    }

}

else {
    echo "Please select whether you are a Student or Administrator.";
}

$conn->close();

?>