<?php
session_start();
include('includes/config.php');

// Redirect to login if not logged in
if (!isset($_SESSION['userlogin'])) {
    header("Location: login.php");
    exit;
}

$statusMsg = "";
$bookingData = null;

if (isset($_POST['track'])) {
    $bookingId = $_POST['bookingId'];
    $userId = $_SESSION['userlogin'];

    $sql = "SELECT * FROM tblbooking WHERE id = :bid AND userId = :uid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bid', $bookingId);
    $query->bindParam(':uid', $userId);
    $query->execute();

    if ($query->rowCount() > 0) {
        $bookingData = $query->fetch(PDO::FETCH_ASSOC);
    } else {
        $statusMsg = "No booking found with this ID or you do not have access.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Booking | DripLab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .tracking-form {
            max-width: 500px;
            margin: 30px auto;
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
        }
        .page-header {
            background: #202C45;
            padding: 30px 0;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<?php include('includes/header.php'); ?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>Track Your Booking</h2>
            </div>
            <div class="col-12">
                <a href="index.php">Home</a>
                <a href="track-booking.php">Track Booking</a>
            </div>
        </div>
    </div>
</div>

<!-- Tracking Form -->
<div class="container">
    <div class="tracking-form">
        <h4 class="text-center mb-4">Enter Your Booking ID</h4>
        <form method="POST">
            <div class="form-group">
                <input type="text" name="bookingId" class="form-control" placeholder="Enter Booking ID (e.g., 9)" required>
            </div>
            <button type="submit" name="track" class="btn btn-danger btn-block">Track Booking</button>
        </form>

        <?php if ($statusMsg): ?>
            <div class="alert alert-danger mt-4"><?= $statusMsg ?></div>
        <?php endif; ?>

        <?php if ($bookingData): ?>
            <div class="mt-4 border rounded p-3 bg-light">
                <h5>Booking Details</h5>
                <p><strong>Booking ID:</strong> <?= $bookingData['id'] ?></p>
                <p><strong>Full Name:</strong> <?= $bookingData['FullName'] ?></p>
                <p><strong>Email:</strong> <?= $bookingData['Email'] ?></p>
                <p><strong>Phone Number:</strong> <?= $bookingData['PhoneNumber'] ?></p>
                <p><strong>Plan:</strong> <?= $bookingData['WashingPlan'] ?></p>
                <p><strong>Vehicle Type:</strong> <?= $bookingData['VehicleType'] ?></p>
                <p><strong>Date:</strong> <?= $bookingData['WashDate'] ?></p>
                <p><strong>Time:</strong> <?= $bookingData['WashTime'] ?></p>
                <p><strong>Location:</strong> <?= $bookingData['Location'] ?></p>
                <p><strong>Status:</strong> 
                    <span class="badge badge-<?= 
                        $bookingData['status'] == 'New' ? 'warning' : 
                        ($bookingData['status'] == 'Completed' ? 'success' : 'danger') ?>">
                        <?= $bookingData['status'] ?>
                    </span>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>

</body>
</html>
