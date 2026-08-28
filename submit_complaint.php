<?php

session_start();


if (!isset($_SESSION['student_id'])) {

    header("Location: login.php");
    exit();
}


include 'config/database.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $student_id = $conn->real_escape_string($_SESSION['student_id']);

    $category = $conn->real_escape_string($_POST['complaint-category']);

    $complaint_title = $conn->real_escape_string($_POST['complaint-title']);

    $complaint_description = $conn->real_escape_string($_POST['complaint-description']);

    $status = "Pending";


    $query = "INSERT INTO complaints 
              (student_id, category, complaint_title, complaint_description, status)
              VALUES
              ('$student_id', '$category', '$complaint_title', '$complaint_description', '$status')";


    $result = $conn->query($query);


    if ($result) {

        header("Location: student_dashboard.php");
        exit();
    } else {

        echo "Complaint submission failed.";
    }
}

?>


<?php include 'includes/header.php'; ?>

<?php include 'includes/navbar.php'; ?>

<main>
    <section class="complaint-hero-section">
        <div class="complaint-hero-container">

            <div class="complaint-hero-content">
                <h1>Submit Your Complaint</h1>
                <p>Report any issue related to university facilities or services through this form. Provide accurate details
                    so the administration can review and resolve your complaint efficiently.</p>
            </div>
        </div>
    </section>

    <section class="complaint-section">
        <div class="complaint-container">
            <div class="complaint-form-wrapper">
                <form action="submit_complaint.php" method="POST">
                    <div class="complaint-category">
                        <h2>Complaint Details</h2>
                        <label for="complaint-category">Complaint Category</label>
                        <select name="complaint-category" id="complaint-category" required>
                            <option value="" disabled selected>Select Complaint Category</option>
                            <option value="Classroom Issue">Classroom Issue</option>
                            <option value="Internet & Wi-Fi">Internet & Wi-Fi</option>
                            <option value="Laboratory Issue">Laboratory Issue</option>
                            <option value="Electricity Problems">Electricity Problems</option>
                            <option value="Water Supply">Water Supply</option>
                            <option value="Cleanliness & Maintenance">Cleanliness & Maintenance</option>
                            <option value="Security Concerns">Security Concerns</option>
                            <option value="Other Issues">Other Issues</option>
                        </select>
                    </div>

                    <div class="complaint-title">
                        <label for="complaint-title">Complaint Title</label>
                        <input type="text" name="complaint-title" id="complaint-title"
                            placeholder="Enter Complaint title (eg. projector not working)" required maxlength="200">
                    </div>

                    <div class="complaint-description">
                        <label for="complaint-description">Complaint Description</label>
                        <textarea name="complaint-description"
                            id="complaint-description"
                            placeholder="Enter complaint in detail..."
                            required rows="6"></textarea>
                    </div>

                    <div class="submit-button">
                        <button type="submit">Submit Complaint</button>
                        <p>Your complaint will be reviewed by the concerned department. You can track its progress from
                            your complaint history.</p>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>

<?php

$conn->close();

?>