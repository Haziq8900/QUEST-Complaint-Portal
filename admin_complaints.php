<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include 'config/database.php';

$complaints_query = "SELECT 
                        complaints.complaint_id,
                        students.full_name,
                        students.roll_number,
                        students.department,
                        complaints.category,
                        complaints.status,
                        complaints.submitted_at
                        FROM complaints
                        INNER JOIN students
                        ON complaints.student_id = students.student_id
                        ORDER BY complaints.complaint_id DESC";

$complaints_result = $conn->query($complaints_query);

?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<main>
    <section class="admin-complaints-hero-section">
        <div class="admin-complaints-hero-container">
            <div class="admin-complaints-hero-content">
                <h1>All Complaints</h1>
                <p>View and manage all complaints submitted by students through the QUEST Complaint Portal.</p>
            </div>
        </div>
    </section>

    <section class="admin-complaints-section">
        <div class="admin-complaints-container">
            <div class="admin-complaints-content">

                <h2>Complaint Management</h2>
                <p>Review complaint details, monitor their status, and take necessary actions for resolution.</p>

                <div class="complaints-table">

                    <table>
                        <thead>
                            <tr>
                                <th>Complaint ID</th>
                                <th>Student Name</th>
                                <th>Roll Number</th>
                                <th>Department</th>
                                <th>Category</th>
                                <th>Date Submitted</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php while ($complaint = $complaints_result->fetch_assoc()) { ?>

                                <tr>

                                    <td><?php echo $complaint['complaint_id']; ?></td>

                                    <td><?php echo $complaint['full_name']; ?></td>

                                    <td><?php echo $complaint['roll_number']; ?></td>

                                    <td><?php echo $complaint['department']; ?></td>

                                    <td><?php echo $complaint['category']; ?></td>

                                    <td><?php echo $complaint['submitted_at']; ?></td>

                                    <td><?php echo $complaint['status']; ?></td>

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

            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>

<?php
$conn->close();
?>