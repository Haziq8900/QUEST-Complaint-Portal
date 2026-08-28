<?php

session_start();

if (!isset($_SESSION['student_id'])) {

    header("Location: login.php");
    exit();

}

include 'config/database.php';


$student_id = $_SESSION['student_id'];
$student_name = $_SESSION['student_name'];


// Total complaints
$total_query = "SELECT COUNT(*) AS total 
                FROM complaints 
                WHERE student_id='$student_id'";

$total_result = $conn->query($total_query);

$total_complaints = $total_result->fetch_assoc()['total'];


// Pending complaints
$pending_query = "SELECT COUNT(*) AS pending 
                  FROM complaints 
                  WHERE student_id='$student_id' 
                  AND status='Pending'";

$pending_result = $conn->query($pending_query);

$pending_complaints = $pending_result->fetch_assoc()['pending'];


// In Progress complaints
$progress_query = "SELECT COUNT(*) AS progress 
                   FROM complaints 
                   WHERE student_id='$student_id' 
                   AND status='In Progress'";

$progress_result = $conn->query($progress_query);

$in_progress_complaints = $progress_result->fetch_assoc()['progress'];


// Resolved complaints
$resolved_query = "SELECT COUNT(*) AS resolved 
                   FROM complaints 
                   WHERE student_id='$student_id' 
                   AND status='Resolved'";

$resolved_result = $conn->query($resolved_query);

$resolved_complaints = $resolved_result->fetch_assoc()['resolved'];


// Recent complaints
$recent_query = "SELECT * FROM complaints 
                 WHERE student_id='$student_id'
                 ORDER BY complaint_id DESC
                 LIMIT 5";

$recent_result = $conn->query($recent_query);

?>


<?php include 'includes/header.php'; ?>

<?php include 'includes/navbar.php'; ?>

<main>
    <section class="welcome-section">
        <div class="welcome-container">

            <div class="welcome-content">
                <h1>Welcome, <?php echo $student_name; ?>!</h1>
                <p>
                    Manage your complaints, track their progress, and stay informed about updates through your personal
                    dashboard.
                </p>
            </div>
        </div>
    </section>

    <section class="stats-section">
        <div class="stats-container">

            <div class="stats-grid">

                <div class="stat-card">
                    <h2>Total Complaints</h2>
                    <span class="stat-number"><?php echo $total_complaints; ?></span>
                </div>

                <div class="stat-card">
                    <h2>Pending</h2>
                    <span class="stat-number"><?php echo $pending_complaints; ?></span>
                </div>

                <div class="stat-card">
                    <h2>In Progress</h2>
                    <span class="stat-number"><?php echo $in_progress_complaints; ?></span>
                </div>

                <div class="stat-card">
                    <h2>Resolved</h2>
                    <span class="stat-number"><?php echo $resolved_complaints; ?></span>
                </div>
            </div>
        </div>
    </section>

    <section class="quick-actions">
        <div class="quick-actions-container">
            <div class="quick-action-content">
                <h2>Quick Actions</h2>
                <p>Access the most frequently used features of your dashboard.</p>
                <a href="submit_complaint.php">Submit New Complaint</a>
                <a href="complaint_history.php">View Complaint History</a>
            </div>
        </div>
    </section>

    <section class="recent-complaints">
        <div class="recent-complaints-container">
            <div class="recent-complaint-content">
                <h2>Recent Complaints</h2>
                <p>
                    View latest complaints you have submitted along with their current status.
                </p>

                <div class="complaint-table">

                    <table>
                        <thead>
                            <tr>
                                <th>Complaint ID</th>
                                <th>Category</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php while($complaint = $recent_result->fetch_assoc()) { ?>

                            <tr>
                                <td><?php echo $complaint['complaint_id']; ?></td>
                                <td><?php echo $complaint['category']; ?></td>
                                <td><?php echo $complaint['submitted_at']; ?></td>
                                <td><?php echo $complaint['status']; ?></td>
                            </tr>

                        <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>

<?php

$conn->close();

?>