<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0) {    
    header('location:index.php');
    exit;
}

if(isset($_POST['update'])) {
    $id = intval($_GET['bid']);
    $ttype = $_POST['txntype'];
    $transactionno = $_POST['transactionno'];    
    $message = $_POST['message'];

    $sql = "UPDATE tblbooking 
            SET adminRemark = :message, 
                paymentMode = :ttype, 
                txnNumber = :transactionno, 
                status = 'Completed' 
            WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':ttype', $ttype, PDO::PARAM_STR);
    $query->bindParam(':transactionno', $transactionno, PDO::PARAM_STR);
    $query->bindParam(':message', $message, PDO::PARAM_STR);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    echo "<script>alert('Booking updated successfully'); window.location='all-bookings.php';</script>";
    exit;
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>CWMS | Booking Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="css/bootstrap.min.css" rel='stylesheet' />
    <link href="css/style.css" rel='stylesheet' />
    <link href="css/font-awesome.css" rel="stylesheet">

    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <style>
        .modal .form-control { margin-bottom: 10px; }
        .modal .modal-title { font-weight: bold; }
        .breadcrumb { margin-top: 10px; }
    </style>
</head>
<body>
<div class="page-container">
    <div class="left-content">
        <div class="mother-grid-inner">
            <?php include('includes/header.php'); ?>

            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a> <i class="fa fa-angle-right"></i> Booking Details</li>
            </ol>

            <div class="agile-grids">
                <div class="agile-tables">
                    <div class="w3l-table-info">
                        <h2>Booking Details #<?= htmlentities($_GET['bid']); ?></h2>

                        <table class="table table-bordered">
                            <tbody>
                            <?php
                            $bid = intval($_GET['bid']);
                            $sql = "SELECT tblbooking.*, tblwashingpoints.WashingPointName 
                                    FROM tblbooking 
                                    LEFT JOIN tblwashingpoints ON tblwashingpoints.id = tblbooking.carWashPoint 
                                    WHERE tblbooking.id = :bid";
                            $query = $dbh->prepare($sql);
                            $query->bindParam(':bid', $bid, PDO::PARAM_INT);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);

                            if ($query->rowCount() > 0) {
                                foreach ($results as $result) {
                            ?>
                            <tr>
                                <th>Booking ID</th>
                                <td><?= htmlentities($result->id); ?></td>
                                <th>Booking Date</th>
                                <td><?= htmlentities($result->BookingDate); ?></td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td><?= htmlentities($result->FullName); ?></td>
                                <th>Phone</th>
                                <td><?= htmlentities($result->PhoneNumber); ?></td>
                            </tr>
                            <tr>
                                <th>Vehicle Type</th>
                                <td><?= htmlentities($result->VehicleType); ?></td>
                                <th>Washing Plan</th>
                                <td><?= htmlentities($result->WashingPlan); ?></td>
                            </tr>
                            <tr>
                                <th>Wash Date</th>
                                <td><?= htmlentities($result->WashDate); ?></td>
                                <th>Wash Time</th>
                                <td><?= htmlentities($result->WashTime); ?></td>
                            </tr>
                            <tr>
                                <th>Washing Point</th>
                                <td colspan="3"><?= htmlentities($result->WashingPointName ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <th>Message (if any)</th>
                                <td colspan="3"><?= htmlentities($result->Message); ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td colspan="3"><?= htmlentities($result->status); ?></td>
                            </tr>

                            <?php if (empty($result->adminRemark)) { ?>
                            <tr>
                                <td colspan="4" class="text-center">
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#actionModal">Take Action</button>
                                </td>
                            </tr>
                            <?php } else { ?>
                            <tr><td colspan="4" class="text-center text-info font-weight-bold">Admin Details</td></tr>
                            <tr>
                                <th>Transaction Type</th>
                                <td><?= htmlentities($result->paymentMode); ?></td>
                                <th>Transaction No.</th>
                                <td><?= htmlentities($result->txnNumber); ?></td>
                            </tr>
                            <tr>
                                <th>Admin Remark</th>
                                <td colspan="3"><?= htmlentities($result->adminRemark); ?></td>
                            </tr>
                            <?php } } } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php include('includes/footer.php'); ?>
        </div>
    </div>

<?php include('includes/sidebarmenu.php'); ?>
</div>

<!-- Modal: Take Action -->
<div class="modal fade" id="actionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Booking</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <select name="txntype" class="form-control" required>
                        <option value="">Select Transaction Type</option>
                        <option value="UPI">UPI</option>
                        <option value="e-Wallet">e-Wallet</option>
                        <option value="Debit/Credit Card">Debit/Credit Card</option>
                        <option value="Cash">Cash</option>
                        <option value="Other">Other</option>
                    </select>
                    <input type="text" name="transactionno" class="form-control" placeholder="Transaction Number (if any)">
                    <textarea name="message" class="form-control" placeholder="Admin Remark" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="update" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
