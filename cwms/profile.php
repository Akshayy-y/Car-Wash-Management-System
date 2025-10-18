<?php
session_start();
include('includes/config.php');

if (!isset($_SESSION['userlogin'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['userlogin'];
$sql = "SELECT fullName, email, mobileNumber FROM tblusers WHERE id = :uid";
$query = $dbh->prepare($sql);
$query->bindParam(':uid', $userId, PDO::PARAM_INT);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile | DripLab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap & Style -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="css/style.css" rel="stylesheet"> <!-- Your site-wide styles -->

    <style>
        .profile-section {
            padding: 60px 0;
            background-color: #f8f9fa;
        }

        .profile-box {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }

        .page-header {
            background: #202C45;
            padding: 40px 0;
            color: #fff;
        }

        .page-header h2 {
            color: #fff;
            margin-bottom: 10px;
        }

        .page-header a {
            color: #f8f9fa;
            text-decoration: none;
            margin-right: 10px;
        }

        .btn-custom {
            background-color: #dc3545;
            color: white;
            transition: 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<?php include('includes/header.php'); ?>

<!-- Page Header Start -->
<div class="page-header text-center">
    <div class="container">
        <h2>User Profile</h2>
        <div>
            <a href="index.php">Home</a> /
            <a href="profile.php">Profile</a>
        </div>
    </div>
</div>
<!-- Page Header End -->

<!-- Profile Section Start -->
<section class="profile-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="profile-box">
                    <h4 class="text-center mb-4">My Profile</h4>
                    <form>
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($user['fullName']) ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Mobile Number</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($user['mobileNumber']) ?>" readonly>
                        </div>
                        <div class="text-center">
                            <a href="edit-profile.php" class="btn btn-custom">Edit Profile</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Profile Section End -->

<?php include('includes/footer.php'); ?>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>

</body>
</html>
