<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>CWMS | New Bookings</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="css/morris.css" type="text/css" />
    <link href="css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="css/table-style.css" />
    <link rel="stylesheet" href="css/basictable.css" />
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/jquery.basictable.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#table').basictable();
        });
    </script>
</head>

<body>
    <div class="page-container">
        <div class="left-content">
            <div class="mother-grid-inner">
                <?php include('includes/header.php'); ?>
                <div class="clearfix"></div>

                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a> <i class="fa fa-angle-right"></i> New Bookings</li>
                </ol>

                <div class="agile-grids">
                    <div class="agile-tables">
                        <div class="w3l-table-info">
                            <h2>New Bookings</h2>
                            <table id="table" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Booking No.</th>
                                        <th>Name</th>
                                        <th>Package Type</th>
                                        <th>Washing Point</th>
                                        <th>Washing Date/Time</th>
                                        <th>Posting Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT tblbooking.*, 
                                                   tblbooking.id AS bid, 
                                                   tblwashingpoints.WashingPointName,
                                                   tblwashingpoints.Location AS washingPointAddress 
                                            FROM tblbooking 
                                            LEFT JOIN tblwashingpoints 
                                              ON tblwashingpoints.id = tblbooking.carWashPoint 
                                            WHERE LOWER(tblbooking.status) = 'new' 
                                            ORDER BY tblbooking.id DESC";

                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {
                                    ?>
                                            <tr>
                                                <td><?php echo htmlentities($result->bid); ?></td>
                                                <td><?php echo htmlentities($result->FullName); ?></td>
                                                <td><?php echo htmlentities($result->WashingPlan); ?></td>
                                                <td>
                                                    <?php echo htmlentities($result->WashingPointName); ?><br>
                                                    <?php echo htmlentities($result->washingPointAddress); ?>
                                                </td>
                                                <td><?php echo htmlentities($result->WashDate . " / " . $result->WashTime); ?></td>
                                                <td><?php echo htmlentities($result->BookingDate); ?></td>
                                                <td>
                                                    <a href="booking-details.php?bid=<?php echo htmlentities($result->bid); ?>" class="btn btn-info btn-sm">View</a>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="7" style="color:red;" class="text-center">No record found</td>
                                        </tr>
                                    <?php } ?>
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

    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>
