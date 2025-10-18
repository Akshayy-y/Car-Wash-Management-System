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
    <title>CWMS | Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8" />

    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet">
    <link href="css/morris.css" rel="stylesheet">
    <link href="css/icon-font.min.css" rel="stylesheet" />

    <script src="js/jquery-2.1.4.min.js"></script>

    <style>
        .four-grid {
            transition: 0.3s;
        }

        .four-grid:hover {
            background-color: #f5f5f5;
            cursor: pointer;
        }

        a.card-link {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>

<body>
    <div class="page-container">
        <div class="left-content">
            <div class="mother-grid-inner">
                <?php include('includes/header.php'); ?>

                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a><i class="fa fa-angle-right"></i> Dashboard</li>
                </ol>

                <!-- Summary Cards -->
                <div class="four-grids">

                    <a href="all-bookings.php" class="card-link">
                        <div class="col-md-3 four-grid">
                            <div class="four-agileits">
                                <div class="icon"><i class="glyphicon glyphicon-list-alt" aria-hidden="true"></i></div>
                                <div class="four-text">
                                    <h3>Total Bookings</h3>
                                    <?php
                                    $sql = "SELECT id FROM tblbooking";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $cnt = $query->rowCount();
                                    ?>
                                    <h4><?php echo htmlentities($cnt); ?></h4>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="new-booking.php" class="card-link">
                        <div class="col-md-3 four-grid">
                            <div class="four-agileinfo">
                                <div class="icon"><i class="glyphicon glyphicon-time" aria-hidden="true"></i></div>
                                <div class="four-text">
                                    <h3>New Bookings</h3>
                                    <?php
                                    $sql1 = "SELECT id FROM tblbooking WHERE status='New'";
                                    $query1 = $dbh->prepare($sql1);
                                    $query1->execute();
                                    $newbookings = $query1->rowCount();
                                    ?>
                                    <h4><?php echo htmlentities($newbookings); ?></h4>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="completed-booking.php" class="card-link">
                        <div class="col-md-3 four-grid">
                            <div class="four-wthree">
                                <div class="icon"><i class="glyphicon glyphicon-ok-circle" aria-hidden="true"></i></div>
                                <div class="four-text">
                                    <h3>Completed Bookings</h3>
                                    <?php
                                    $sql3 = "SELECT id FROM tblbooking WHERE status='Completed'";
                                    $query3 = $dbh->prepare($sql3);
                                    $query3->execute();
                                    $completedbookings = $query3->rowCount();
                                    ?>
                                    <h4><?php echo htmlentities($completedbookings); ?></h4>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="manage-enquires.php" class="card-link">
                        <div class="col-md-3 four-grid">
                            <div class="four-w3ls">
                                <div class="icon"><i class="glyphicon glyphicon-envelope" aria-hidden="true"></i></div>
                                <div class="four-text">
                                    <h3>Enquiries</h3>
                                    <?php
                                    $sql2 = "SELECT id FROM tblenquiry";
                                    $query2 = $dbh->prepare($sql2);
                                    $query2->execute();
                                    $cnt2 = $query2->rowCount();
                                    ?>
                                    <h4><?php echo htmlentities($cnt2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </a>

                    <div class="clearfix"></div>
                </div>

                <div class="four-grids">
                    <a href="managecar-washingpoints.php" class="card-link">
                        <div class="col-md-3 four-grid">
                            <div class="four-w3ls">
                                <div class="icon"><i class="glyphicon glyphicon-map-marker" aria-hidden="true"></i></div>
                                <div class="four-text">
                                    <h3>Washing Points</h3>
                                    <?php
                                    $sql5 = "SELECT id FROM tblwashingpoints";
                                    $query5 = $dbh->prepare($sql5);
                                    $query5->execute();
                                    $washingpoints = $query5->rowCount();
                                    ?>
                                    <h4><?php echo htmlentities($washingpoints); ?></h4>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="manage-users.php" class="card-link">
                        <div class="col-md-3 four-grid">
                            <div class="four-w3ls">
                                <div class="icon"><i class="glyphicon glyphicon-user" aria-hidden="true"></i></div>
                                <div class="four-text">
                                    <h3>Registered Users</h3>
                                    <?php
                                    $sql6 = "SELECT id FROM tblusers";
                                    $query6 = $dbh->prepare($sql6);
                                    $query6->execute();
                                    $usercount = $query6->rowCount();
                                    ?>
                                    <h4><?php echo htmlentities($usercount); ?></h4>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="analytics.php" class="card-link">
                        <div class="col-md-3 four-grid">
                            <div class="four-agileinfo">
                                <div class="icon"><i class="fa fa-bar-chart" style="color: white; font-size:2.4rem;" aria-hidden="true"></i></div>
                                <div class="four-text">
                                    <h3>Analytics</h3>
                                    <h4>➤</h4>
                                </div>
                            </div>
                        </div>
                    </a>



                    <div class="clearfix"></div>
                </div>

                <div class="inner-block"></div>
                <?php include('includes/footer.php'); ?>
            </div>
        </div>

        <?php include('includes/sidebarmenu.php'); ?>
        <div class="clearfix"></div>
    </div>

    <!-- Sidebar Toggle Script -->
    <script>
        var toggle = true;
        $(".sidebar-icon").click(function() {
            if (toggle) {
                $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
                $("#menu span").css({
                    "position": "absolute"
                });
            } else {
                $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
                setTimeout(function() {
                    $("#menu span").css({
                        "position": "relative"
                    });
                }, 400);
            }
            toggle = !toggle;
        });
    </script>

    <!-- Other Scripts -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/raphael-min.js"></script>
    <script src="js/morris.js"></script>
</body>

</html>