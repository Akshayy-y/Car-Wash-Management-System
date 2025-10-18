<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {	
    header('location:index.php');
    exit();
} else {
    // Code for Deletion
    if (isset($_GET['rid'])) {
        $id = intval($_GET['rid']);
        $sql = "DELETE FROM tblwashingpoints WHERE id = :id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        echo "<script>alert('Record Deleted');</script>";
        echo "<script>window.location.href ='managecar-washingpoints.php'</script>";
    }
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>CWMS | Manage Car Wash Point</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="css/morris.css" type="text/css"/>
    <link href="css/font-awesome.css" rel="stylesheet"> 
    <link rel="stylesheet" type="text/css" href="css/table-style.css" />
    <link rel="stylesheet" type="text/css" href="css/basictable.css" />
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/jquery.basictable.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table').basictable();
        });
    </script>
</head> 
<body>
    <div class="page-container">
        <div class="left-content">
            <div class="mother-grid-inner">
                <?php include('includes/header.php'); ?>
                <div class="clearfix"> </div>	
            </div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a><i class="fa fa-angle-right"></i>Manage Car Washing Points</li>
            </ol>
            <div class="agile-grids">	
                <div class="agile-tables">
                    <div class="w3l-table-info">
                        <h2>Manage Car Washing Points</h2>
                        <table id="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Washing Point Name</th>
                                    <th>Address</th>
                                    <th>Contact Number</th>
                                    <th>Creation Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM tblwashingpoints";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $cnt = 1;
                                if ($query->rowCount() > 0) {
                                    foreach ($results as $result) {
                                ?>		
                                <tr>
                                    <td><?php echo htmlentities($cnt); ?></td>
                                    <td><?php echo htmlentities($result->WashingPointName); ?></td>
                                    <td><?php echo htmlentities($result->Location); ?></td>
                                    <td><?php echo htmlentities($result->ContactNumber); ?></td>
                                    <td><?php echo htmlentities($result->CreationDate); ?></td>
                                    <td>
                                        <a href="editcar-washpoint.php?wpid=<?php echo htmlentities($result->id); ?>">Edit</a> |
                                        <a href="managecar-washingpoints.php?rid=<?php echo htmlentities($result->id); ?>" style="color:red;" onClick="return confirm('Do you really want to delete');">Delete</a>
                                    </td>
                                </tr>
                                <?php $cnt++; } } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    <?php include('includes/sidebarmenu.php'); ?>
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>
