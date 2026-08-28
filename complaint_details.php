<?php

session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

include 'config/database.php';


if (!isset($_GET['id'])) {

    header("Location: complaint_history.php");
    exit();
}

$complaint_id = $_GET['id'];

$student_id = $_SESSION['student_id'];

// Fetch complaint details
$query = "SELECT * FROM complaints 
            WHERE complaint_id='$complaint_id'
            AND student_id='$student_id'";


$result = $conn->query($query);

if ($result->num_rows == 0) {
    echo "Complaint not found.";
    exit();
}


$complaint = $result->fetch_assoc();

?>


<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<main>
    <section class="complaint-details-hero-section">
        <div class="complaint-details-hero-container">

            <div class="complaint-details-hero-content">
                <h1>Complaint Details</h1>
                <p>
                    View complete information about your submitted complaint, including its details, current status, and
                    response from the concerned department.
                </p>
            </div>
        </div>
    </section>

    <section class="complaint-details-section">
        <div class="complaint-details-container">
            <div class="complaint-details-content">
                <h2>Complaint Information</h2>
                <p>
                    Below are the complete details of your submitted complaint.
                </p>
            </div>

            <div class="complaint-information-grid">

                <div class="complaint-information-card">
                    <h3>Complaint ID</h3>
                    <p>#<?php echo $complaint['complaint_id']; ?></p>
                </div>

                <div class="complaint-information-card">
                    <h3>Category</h3>
                    <p><?php echo $complaint['category']; ?></p>
                </div>

                <div class="complaint-information-card">
                    <h3>Title</h3>
                    <p><?php echo $complaint['complaint_title']; ?></p>
                </div>

                <div class="complaint-information-card">
                    <h3>Description</h3>
                    <p><?php echo $complaint['complaint_description']; ?></p>
                </div>

                <div class="complaint-information-card">
                    <h3>Submitted Date</h3>
                    <p><?php echo $complaint['submitted_at']; ?></p>
                </div>

                <div class="complaint-information-card">
                    <h3>Status</h3>
                    <p><?php echo $complaint['status']; ?></p>
                </div>

                <div class="complaint-information-card">
                    <h3>Admin Response</h3>
                    <p>
                        <?php

                        if ($complaint['admin_response'] == NULL) {

                            echo "No response available yet.";
                        } else {

                            echo $complaint['admin_response'];
                        }

                        ?>
                    </p>
                </div>
            </div>

            <div class="back-button">
                <a href="complaint_history.php">Back to Complaint History</a>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>

<?php

$conn->close();

?>