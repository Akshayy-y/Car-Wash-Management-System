<?php
session_start();
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

$msg = "";
$error = "";

$type = $_POST['type'] ?? ($_GET['type'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] === "Update" && $type != '') {
    $pagedetails = $_POST['pgedetails'] ?? '';

    $sql = "UPDATE tblpages SET PageDescription = :pagedetails WHERE PageType = :pagetype";
    $query = $dbh->prepare($sql);
    $query->bindParam(':pagedetails', $pagedetails, PDO::PARAM_STR);
    $query->bindParam(':pagetype', $type, PDO::PARAM_STR);

    if ($query->execute()) {
        $msg = "Page data updated successfully";
    } else {
        $error = "Something went wrong. Please try again.";
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>CWMS | About Us Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">

    <!-- Stylesheets -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet">
    <link href="css/icon-font.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">

    <script src="js/jquery-2.1.4.min.js"></script>

    <style>
        .errorWrap {
            padding: 10px;
            margin-bottom: 15px;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .1);
        }

        .succWrap {
            padding: 10px;
            margin-bottom: 15px;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .1);
        }

        textarea {
            width: 100%;
            min-height: 250px;
            resize: vertical;
            font-family: 'Montserrat', sans-serif;
            padding: 10px;
        }
    </style>
</head>

<body>
<div class="page-container">
    <div class="left-content">
        <div class="mother-grid-inner">
            <?php include('includes/header.php'); ?>
        </div>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php">Home</a><i class="fa fa-angle-right"></i>Update Page Data</li>
        </ol>

        <div class="grid-form">
            <div class="grid-form1">
                <h3>Update Page Data</h3>

                <?php if ($error): ?>
                    <div class="errorWrap"><strong>ERROR</strong>: <?= htmlentities($error) ?></div>
                <?php elseif ($msg): ?>
                    <div class="succWrap"><strong>SUCCESS</strong>: <?= htmlentities($msg) ?></div>
                <?php endif; ?>

                <form class="form-horizontal" method="post">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Select Page</label>
                        <div class="col-sm-8">
                            <select class="form-control" onchange="window.location.href=this.value;">
                                <option value="" disabled <?= ($type == '') ? 'selected' : '' ?>>-- Select One --</option>
                                <option value="about.php?type=aboutus" <?= ($type == 'aboutus') ? 'selected' : '' ?>>About Us</option>
                            </select>
                        </div>
                    </div>

                    <?php if ($type == 'aboutus'): ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Selected Page</label>
                            <div class="col-sm-8"><strong>About Us</strong></div>
                        </div>

                        <?php
                        $sql = "SELECT PageDescription FROM tblpages WHERE PageType = :pagetype LIMIT 1";
                        $query = $dbh->prepare($sql);
                        $query->bindParam(':pagetype', $type, PDO::PARAM_STR);
                        $query->execute();
                        $result = $query->fetch(PDO::FETCH_OBJ);
                        ?>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Page Content</label>
                            <div class="col-sm-8">
                                <textarea name="pgedetails" required><?= htmlentities($result->PageDescription ?? '') ?></textarea>
                            </div>
                        </div>

                        <input type="hidden" name="type" value="<?= htmlentities($type) ?>">

                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2 mt-3">
                                <button type="submit" name="submit" value="Update" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="inner-block"></div>
        <?php include('includes/footer.php'); ?>
    </div>
</div>

<?php include('includes/sidebarmenu.php'); ?>

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
