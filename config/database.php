<?php

// Database server name
// Since MySQL is running on your local machine, we use localhost
$host = "localhost";


// MySQL username
// Default MySQL username is usually root
$username = "root";


// MySQL password
// If you created a password during MySQL installation, write it here
// If your root account has no password, leave it empty
$password = "HaziqKhan@30-01-2005";


// Name of the database we created in MySQL Workbench
$database = "quest_complaint_portal";


// Create a connection between PHP and MySQL database
$conn = new mysqli($host, $username, $password, $database);


// Check if connection failed
if ($conn->connect_error) {

    // Stop the program and display the error message
    die("Database Connection Failed: " . $conn->connect_error);

}


// If the connection is successful, PHP will continue running
// No message is displayed because we don't want users to see database details

?>