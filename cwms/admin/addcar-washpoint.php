<?php
session_start();

// Turn ON errors for debugging during development
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
} else {
    if (isset($_POST['submit'])) {
        $wpname = $_POST['washingpointname'];
        $wpaddress = $_POST['address'];
        $wpcnumber = $_POST['contactno'];

        try {
            $sql = "INSERT INTO tblwashingpoints(WashingPointName, Location, ContactNumber) 
                    VALUES(:wpname, :wpaddress, :wpcnumber)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':wpname', $wpname, PDO::PARAM_STR);
            $query->bindParam(':wpaddress', $wpaddress, PDO::PARAM_STR);
            $query->bindParam(':wpcnumber', $wpcnumber, PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();

            if ($lastInsertId) {
                echo "<script>alert('Car wash point added successfully');</script>";
                echo "<script>window.location.href ='addcar-washpoint.php'</script>";
            } else {
                echo "<script>alert('Something went wrong. Please try again.');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>CWMS | Add Washing Point</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel='stylesheet' type='text/css'/>
    <link href="css/style.css" rel='stylesheet' type='text/css'/>
    <link rel="stylesheet" href="css/morris.css" type="text/css"/>
    <link href="css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="css/icon-font.min.css" type='text/css'/>
    <link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'/>
    <link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'/>
    <script src="js/jquery-2.1.4.min.js"></script>
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
    </style>
</head>
<body>
<div class="page-container">
    <div class="left-content">
        <div class="mother-grid-inner">
            <?php include('includes/header.php'); ?>
            <div class="clearfix"></div>
        </div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php">Home</a><i class="fa fa-angle-right"></i>Add Washing Point</li>
        </ol>

        <div class="grid-form">
            <div class="grid-form1">
                <h3>Add Washing Point</h3>
                <div class="tab-content">
                    <div class="tab-pane active" id="horizontal-form">
                        <form class="form-horizontal" name="washingpoint" method="post">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Car Wash Point Name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="washingpointname" placeholder="Washing Point Name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Address (Location)</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" name="address" placeholder="Address / Location" required rows="4"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Contact Number</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="contactno" placeholder="Contact Number" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <button type="submit" name="submit" class="btn-primary btn">Add</button>
                                    <button type="reset" class="btn-inverse btn">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="panel-footer"></div>
            </div>
        </div>

        <div class="inner-block"></div>
        <?php include('includes/footer.php'); ?>
    </div>
</div>

<?php include('includes/sidebarmenu.php'); ?>
<div class="clearfix"></div>

<script>
    var toggle = true;
    $(".sidebar-icon").click(function () {
        if (toggle) {
            $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
            $("#menu span").css({"position": "absolute"});
        } else {
            $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
            setTimeout(function () {
                $("#menu span").css({"position": "relative"});
            }, 400);
        }
        toggle = !toggle;
    });
</script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>
