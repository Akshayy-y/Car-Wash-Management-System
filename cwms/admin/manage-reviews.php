<?php
session_start();
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>CWMS | Manage Reviews</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/font-awesome.css" rel="stylesheet">
</head>
<body>
<div class="page-container">
    <div class="left-content">
        <div class="mother-grid-inner">
            <?php include('includes/header.php'); ?>

            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a> <i class="fa fa-angle-right"></i> Manage Reviews</li>
            </ol>

            <div class="agile-grids">
                <div class="agile-tables">
                    <div class="w3l-table-info">
                        <h2>User Reviews</h2>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Booking ID</th>
                                    <th>Rating</th>
                                    <th>Review</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT tblreviews.*, tblusers.fullName 
                                        FROM tblreviews 
                                        JOIN tblusers ON tblusers.id = tblreviews.userId 
                                        ORDER BY tblreviews.reviewDate DESC";

                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $cnt = 1;

                                if ($query->rowCount() > 0) {
                                    foreach ($results as $row) {
                                        echo "<tr>";
                                        echo "<td>" . $cnt++ . "</td>";
                                        echo "<td>" . htmlentities($row->fullName) . "</td>";
                                        echo "<td>#". htmlentities($row->bookingId) . "</td>";
                                        echo "<td>" . str_repeat("★", $row->rating) . "</td>";
                                        echo "<td>" . htmlentities($row->review) . "</td>";
                                        echo "<td>" . htmlentities($row->reviewDate) . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No reviews found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php include('includes/footer.php'); ?>
        </div>
    </div>

    <?php include('includes/sidebarmenu.php'); ?>
    <div class="clearfix"></div>
</div>

<!-- Scripts -->
<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
