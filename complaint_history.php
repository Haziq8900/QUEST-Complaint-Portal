<?php

session_start();

if (!isset($_SESSION['student_id'])) {

    header("Location: login.php");
    exit();
}

include 'config/database.php';


$student_id = $_SESSION['student_id'];


// Fetch student's complaints
$query = "SELECT * FROM complaints 
            WHERE student_id='$student_id'
            ORDER BY complaint_id DESC";


$result = $conn->query($query);

?>


<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<main>
    <section class="complaint-hero-section">
        <div class="complaint-hero-container">

            <div class="complaint-hero-content">
                <h1>Complaint History</h1>
                <p>
                    View all your submitted complaints, monitor their current status, and track the progress of each issue
                    until resolution.
                </p>
            </div>
        </div>
    </section>

    <section class="complaint-history-section">
        <div class="complaint-history-container">

            <div class="complaint-history-content">
                <h2>Your Complaint History</h2>
                <p>
                    Review all your submitted complaints, check their current status, and monitor the progress of each issue
                    from submission to resolution.
                </p>

                <div class="complaint-history-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Complaint ID</th>
                                <th>Category</th>
                                <th>Title</th>
                                <th>Date Submitted</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php while ($complaint = $result->fetch_assoc()) { ?>

                                <tr>

                                    <td>
                                        <?php echo $complaint['complaint_id']; ?>
                                    </td>

                                    <td>
                                        <?php echo $complaint['category']; ?>
                                    </td>

                                    <td>
                                        <?php echo $complaint['complaint_title']; ?>
                                    </td>

                                    <td>
                                        <?php echo $complaint['submitted_at']; ?>
                                    </td>

                                    <td>
                                        <?php echo $complaint['status']; ?>
                                    </td>

                                    <td>
                                        <a href="complaint_details.php?id=<?php echo $complaint['complaint_id']; ?>">
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