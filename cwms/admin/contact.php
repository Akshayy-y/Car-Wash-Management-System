<?php
session_start();
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

if (isset($_POST['update'])) {
    $address = $_POST['address'];
    $opening = $_POST['openinghrs'];
    $email = $_POST['emailid'];
    $phone = $_POST['contactno'];

    $sql = "UPDATE tblpages SET detail = :address, OpeningHrs = :opening, emailId= :email, phoneNumber = :phone WHERE PageType = 'contactus'";
    $query = $dbh->prepare($sql);
    $query->bindParam(':address', $address, PDO::PARAM_STR);
    $query->bindParam(':opening', $opening, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':phone', $phone, PDO::PARAM_STR);
    $query->execute();

    echo "<script>alert('Details updated successfully');</script>";
    echo "<script>window.location.href ='contact.php'</script>";
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>CWMS | Contact Info</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/style.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/font-awesome.css"/>
    <link rel="stylesheet" href="css/icon-font.min.css"/>

    <style>
        .form-section {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }
        .form-section h3 {
            margin-bottom: 25px;
            color: #337ab7;
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
            <li class="breadcrumb-item"><a href="dashboard.php">Home</a> <i class="fa fa-angle-right"></i> Contact Info</li>
        </ol>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="form-section">
                        <h3>Update Contact Information</h3>
                        <?php 
                        $sql = "SELECT * FROM tblpages WHERE PageType = 'contactus'";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                        if ($results) {
                            foreach ($results as $result) {
                        ?>
                        <form method="post">
                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="address" rows="3" class="form-control" required><?= htmlentities($result->detail); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Opening Hours</label>
                                <input type="text" name="openinghrs" class="form-control" required value="<?= htmlentities($result->openingHrs); ?>">
                            </div>
                            <div class="form-group">
                                <label>Email ID</label>
                                <input type="email" name="emailid" class="form-control" required value="<?= htmlentities($result->emailId); ?>">
                            </div>
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input type="text" name="contactno" class="form-control" required value="<?= htmlentities($result->phoneNumber); ?>">
                            </div>
                            <button type="submit" name="update" class="btn btn-primary">Update</button>
                        </form>
                        <?php
                            }
                        } else {
                            echo "<div class='alert alert-warning'>No contact page found in database.</div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <?php include('includes/footer.php'); ?>
    </div>
</div>

<?php include('includes/sidebarmenu.php'); ?>

<!-- Sidebar Toggle Script -->
<script>
var toggle = true;
$(".sidebar-icon").click(function () {
    if (toggle) {
        $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
        $("#menu span").css({ "position": "absolute" });
    } else {
        $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
        setTimeout(function () {
            $("#menu span").css({ "position": "relative" });
        }, 400);
    }
    toggle = !toggle;
});
</script>

<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
