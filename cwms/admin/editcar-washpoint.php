<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

if (isset($_POST['submit'])) {
    $id = $_GET['wpid'];
    $wpname = $_POST['washingpointname'];
    $wpaddress = $_POST['address'];
    $wpcnumber = $_POST['contactno'];

    $sql = "UPDATE tblwashingpoints 
            SET WashingPointName = :wpname, 
                Location = :wpaddress, 
                ContactNumber = :wpcnumber 
            WHERE id = :id";

    $query = $dbh->prepare($sql);
    $query->bindParam(':wpname', $wpname, PDO::PARAM_STR);
    $query->bindParam(':wpaddress', $wpaddress, PDO::PARAM_STR);
    $query->bindParam(':wpcnumber', $wpcnumber, PDO::PARAM_STR);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    echo "<script>alert('Car wash point updated successfully');</script>";
    echo "<script>window.location.href ='managecar-washingpoints.php'</script>";
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>CWMS | Edit Washing Point</title>
    <link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="css/morris.css" type="text/css" />
    <link href="css/font-awesome.css" rel="stylesheet"> 
    <script src="js/jquery-2.1.4.min.js"></script>
    <link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'/>
    <link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
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
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a><i class="fa fa-angle-right"></i>Edit Washing Point</li>
            </ol>
            <div class="grid-form">
                <div class="grid-form1">
                    <h3>Edit Washing Point</h3>
                    <?php
                    $id = $_GET['wpid'];
                    $sql = "SELECT * FROM tblwashingpoints WHERE id = :id";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':id', $id, PDO::PARAM_INT);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                    foreach ($results as $result) {
                    ?>
                    <form class="form-horizontal" name="washingpoint" method="post">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Car Wash Point Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="washingpointname" value="<?php echo htmlentities($result->WashingPointName); ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Address</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="address" required rows="4"><?php echo htmlentities($result->Location); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Contact Number</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="contactno" value="<?php echo htmlentities($result->ContactNumber); ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <button type="submit" name="submit" class="btn-primary btn">Update</button>
                            </div>
                        </div>
                    </form>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php include('includes/footer.php'); ?>
    </div>
    <?php include('includes/sidebarmenu.php'); ?>
    <script>
        $(".sidebar-icon").click(function () {
            $(".page-container").toggleClass("sidebar-collapsed sidebar-collapsed-back");
            $("#menu span").css("position", $(".page-container").hasClass("sidebar-collapsed") ? "absolute" : "relative");
        });
    </script>
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
