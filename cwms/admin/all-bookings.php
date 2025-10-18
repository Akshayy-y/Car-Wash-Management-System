<?php
session_start();
error_reporting(0);
include('includes/config.php'); // $dbh defined here

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

// ---- Booking status update ----
if (isset($_POST['updateStatus'])) {
    $bookingId = intval($_POST['bookingId']);
    $status = $_POST['status'];

    if (!empty($bookingId) && !empty($status)) {
        // Update booking status
        $updateSql = "UPDATE tblbooking SET Status = :status WHERE id = :id";
        $updateStmt = $dbh->prepare($updateSql);
        $updateStmt->bindParam(':status', $status, PDO::PARAM_STR);
        $updateStmt->bindParam(':id', $bookingId, PDO::PARAM_INT);
        $updateStmt->execute();

        // If completed → notify user
        if ($status === 'Completed') {
            $sql = "SELECT UserId FROM tblbooking WHERE id = :id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':id', $bookingId, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $userid = $row['UserId'];
                $notifSql = "INSERT INTO tblnotifications (userId, message) VALUES (:userid, :message)";
                $notifQuery = $dbh->prepare($notifSql);
                $notifQuery->bindParam(':userid', $userid, PDO::PARAM_INT);
                $notifMsg = "Your booking #$bookingId has been marked as Completed. Thank you!";
                $notifQuery->bindParam(':message', $notifMsg, PDO::PARAM_STR);
                $notifQuery->execute();
            }
        }

        echo "<script>alert('Booking status updated successfully!');</script>";
        echo "<script>window.location.href='all-bookings.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | All Bookings</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="css/font-awesome.css" rel="stylesheet">

        <link href="css/bootstrap.min.css" rel='stylesheet' />
    <link href="css/style.css" rel='stylesheet' />
    <link href="css/font-awesome.css" rel="stylesheet">

    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <style>
        body { background: #f8f9fa; }
        .table-responsive { margin-top: 25px; }
        .badge { font-size: 0.9rem; }
        .action-form select { min-width: 130px; }
        .page-title { font-weight: 600; margin-bottom: 20px; }
    </style>
</head>
<body>
<div class="page-container">
    <div class="left-content">
        <div class="mother-grid-inner">
            <?php include('includes/header.php'); ?>

            <div class="container-fluid px-4">
                <h2 class="page-title">📋 All Bookings</h2>

                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered align-middle text-center shadow-sm">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Plan</th>
                                <th>Location</th>
                                <th>Wash Date & Time</th>
                                <th>Booked On</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sql = "SELECT *, id as bid FROM tblbooking ORDER BY id DESC";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                        if ($query->rowCount() > 0) {
                            $cnt = 1;
                            foreach ($results as $result) {
                        ?>
                            <tr>
                                <td><?= htmlentities($cnt); ?></td>
                                <td><span class="fw-bold">#<?= htmlentities($result->bid); ?></span></td>
                                <td><?= htmlentities($result->FullName); ?></td>
                                <td><?= htmlentities($result->WashingPlan); ?></td>
                                <td><?= htmlentities($result->Location); ?></td>
                                <td><?= htmlentities($result->WashDate . ' / ' . $result->WashTime); ?></td>
                                <td><?= htmlentities($result->BookingDate); ?></td>
                                <td>
                                    <?php if ($result->status == 'New') { ?>
                                        <span class="badge bg-warning text-dark">New</span>
                                    <?php } elseif ($result->Status == 'Completed') { ?>
                                        <span class="badge bg-success">Completed</span>
                                    <?php } elseif ($result->Status == 'Cancelled') { ?>
                                        <span class="badge bg-danger">Cancelled</span>
                                    <?php } elseif ($result->Status == 'In Progress') { ?>
                                        <span class="badge bg-info text-dark">In Progress</span>
                                    <?php } else { ?>
                                        <span class="badge bg-secondary">N/A</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a href="booking-details.php?bid=<?= htmlentities($result->bid); ?>" 
                                       class="btn btn-sm btn-primary mb-1">
                                       <i class="fa fa-eye"></i> View
                                    </a>

                                    <!-- Update Status Form -->
                                    <form method="post" class="action-form d-inline">
                                        <input type="hidden" name="bookingId" value="<?= $result->bid; ?>">
                                        <select name="status" class="form-select form-select-sm d-inline w-auto">
                                            <option value="">--Update--</option>
                                            <option value="In Progress">In Progress</option>
                                            <option value="Completed">Completed</option>
                                            <option value="Cancelled">Cancelled</option>
                                        </select>
                                        <button type="submit" name="updateStatus" class="btn btn-sm btn-success">
                                            <i class="fa fa-check"></i> Save
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                                $cnt++;
                            }
                        } else {
                        ?>
                            <tr>
                                <td colspan="9" class="text-center text-danger fw-bold">No Record Found</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php include('includes/footer.php'); ?>
        </div>
    </div>

    <?php include('includes/sidebarmenu.php'); ?>
    <div class="clearfix"></div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
