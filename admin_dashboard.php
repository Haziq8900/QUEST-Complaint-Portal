<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include 'config/database.php';

$admin_id = $_SESSION['admin_id'];
$admin_name = $_SESSION['admin_name'];

$total_complaints_query = "SELECT COUNT(*) AS total FROM complaints";
$total_complaints_result = $conn->query($total_complaints_query);
$total_complaints = $total_complaints_result->fetch_assoc()['total'];

$pending_query = "SELECT COUNT(*) AS pending FROM complaints WHERE status='Pending'";
$pending_result = $conn->query($pending_query);
$pending_complaints = $pending_result->fetch_assoc()['pending'];

$in_progress_query = "SELECT COUNT(*) AS in_progress FROM complaints WHERE status='In Progress'";
$in_progress_result = $conn->query($in_progress_query);
$in_progress_complaints = $in_progress_result->fetch_assoc()['in_progress'];

$resolved_query = "SELECT COUNT(*) AS resolved FROM complaints WHERE status='Resolved'";
$resolved_result = $conn->query($resolved_query);
$resolved_complaints = $resolved_result->fetch_assoc()['resolved'];


$recent_complaints_query = "SELECT 
                                complaints.complaint_id,
                                students.full_name,
                                students.department,
                                complaints.category,
                                complaints.submitted_at,
                                complaints.status
                                FROM complaints
                                INNER JOIN students
                                ON complaints.student_id = students.student_id
                                ORDER BY complaints.complaint_id DESC
                                LIMIT 5";


$recent_complaints_result = $conn->query($recent_complaints_query);

?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<main>

    <section class="welcome-section">
        <div class="welcome-section-container">

            <div class="welcome-section-content">
                <h1>
                    Welcome, <?php echo $admin_name; ?>!
                </h1>
                <p>
                    Manage complaints and monitor student issues through the QUEST Complaint Portal administration dashboard.
                </p>
            </div>

        </div>
    </section>

    <section class="stats-section">
        <div class="stats-container">

            <h2>Complaint Overview</h2>
            <div class="stats-grid">

                <div class="stat-card">
                    <h3>Total Complaints</h3>
                    <span class="stat-number"><?php echo $total_complaints; ?></span>
                </div>

                <div class="stat-card">
                    <h3>Pending Complaints</h3>
                    <span class="stat-number"><?php echo $pending_complaints; ?></span>
                </div>

                <div class="stat-card">
                    <h3>In Progress</h3>
                    <span class="stat-number"><?php echo $in_progress_complaints; ?></span>
                </div>

                <div class="stat-card">
                    <h3>Resolved</h3>
                    <span class="stat-number"><?php echo $resolved_complaints; ?></span>
                </div>

            </div>
        </div>
    </section>


    <section class="complaint-management-section">
        <div class="complaint-management-container">

            <div class="complaint-management-content">
                <h2>
                    Manage Complaints
                </h2>
                <p>
                    Review submitted complaints, update their status, provide responses, and coordinate with concerned 
                    departments for effective resolution.
                </p>
            </div>

        </div>
    </section>



    <section class="recent-complaints-section">
        <div class="recent-complaints-container">

            <h2>
                Recent Complaints
            </h2>
            <p>
                View the latest complaints submitted by students along with their details and current status.
            </p>

            <table>
                <thead>
                    <tr>
                        <th>Complaint ID</th>
                        <th>Student Name</th>
                        <th>Department</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($complaint = $recent_complaints_result->fetch_assoc()) { ?>
                        <tr>
                            <td>
                                <?php echo $complaint['complaint_id']; ?>
                            </td>

                            <td>
                                <?php echo $complaint['full_name']; ?>
                            </td>

                            <td>
                                <?php echo $complaint['department']; ?>
                            </td>

                            <td>
                                <?php echo $complaint['category']; ?>
                            </td>

                            <td>
                                <?php echo $complaint['submitted_at']; ?>
                            </td>

                            <td>
                                <?php echo $complaint['status']; ?>
                            </td>

                            <td>
                                <a href="admin_complaint_details.php?id=<?php echo $complaint['complaint_id']; ?>">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </section>


    <section class="quick-actions-section">
        <div class="quick-actions-container">

            <h2>
                Quick Actions
            </h2>
            <p>
                Access frequently used administrative features to manage complaints efficiently.
            </p>

            <div class="action-buttons">
                <a href="admin_complaints.php">
                    View All Complaints
                </a>

                <a href="admin_complaints.php">
                    Manage Complaints
                </a>

            </div>
        </div>
    </section>
</main>


<?php include 'includes/footer.php'; ?>

<?php
$conn->close();
?>