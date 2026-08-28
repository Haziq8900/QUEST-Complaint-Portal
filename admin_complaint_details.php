<?php

session_start();

if (!isset($_SESSION['admin_id'])) {

    header("Location: login.php");
    exit();
}

include 'config/database.php';


if (!isset($_GET['id'])) {

    header("Location: admin_complaints.php");
    exit();
}


$complaint_id = $_GET['id'];


$query = "SELECT 
            complaints.complaint_id,
            complaints.category,
            complaints.complaint_title,
            complaints.complaint_description,
            complaints.status,
            complaints.admin_response,
            complaints.submitted_at,
            students.full_name,
            students.roll_number,
            students.department
            FROM complaints
            INNER JOIN students
            ON complaints.student_id = students.student_id
            WHERE complaints.complaint_id='$complaint_id'";


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
                    Review complaint information and update its status according to the progress.
                </p>
            </div>
        </div>
    </section>

    <section class="complaint-details-section">
        <div class="complaint-details-container">

            <h2>Complaint Information</h2>
            <div class="complaint-information-grid">

                <div class="complaint-information-card">
                    <h3>Complaint ID</h3>
                    <p><?php echo $complaint['complaint_id']; ?></p>
                </div>

                <div class="complaint-information-card">
                    <h3>Student Name</h3>
                    <p><?php echo $complaint['full_name']; ?></p>
                </div>

                <div class="complaint-information-card">
                    <h3>Roll Number</h3>
                    <p><?php echo $complaint['roll_number']; ?></p>
                </div>

                <div class="complaint-information-card">
                    <h3>Department</h3>
                    <p><?php echo $complaint['department']; ?></p>
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
                    <h3>Date Submitted</h3>
                    <p><?php echo $complaint['submitted_at']; ?></p>
                </div>
                <div class="complaint-information-card">
                    <h3>Current Status</h3>
                    <p><?php echo $complaint['status']; ?></p>
                </div>

                <div class="complaint-information-card">
                    <h3>Admin Response</h3>
                    <p>
                        <?php
                        if ($complaint['admin_response'] == NULL) {
                            echo "No response added yet.";
                        } else {
                            echo $complaint['admin_response'];
                        }

                        ?>
                    </p>
                </div>
            </div>

            <section class="update-status-section">
                <h2>Update Complaint Status</h2>
                <form action="update_complaint.php" method="POST">

                    <input type="hidden"
                        name="complaint_id"
                        value="<?php echo $complaint['complaint_id']; ?>">

                    <label for="status">
                        Status
                    </label>
                    <select name="status" id="status">
                        <option value="Pending">
                            Pending
                        </option>

                        <option value="In Progress">
                            In Progress
                        </option>

                        <option value="Resolved">
                            Resolved
                        </option>
                    </select>

                    <label for="response">
                        Admin Response
                    </label>

                    <textarea
                        name="response"
                        id="response"
                        placeholder="Write response for student..."></textarea>

                    <button type="submit">
                        Update Complaint
                    </button>
                </form>
            </section>

            <a href="admin_complaints.php">
                Back to All Complaints
            </a>

        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>

<?php

$conn->close();

?>