<?php
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Drip Labs | About Us</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"> 

    <!-- CSS Libraries -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="lib/flaticon/font/flaticon.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
<?php include_once('includes/header.php'); ?>

<!-- Page Header Start -->
<div class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>About Us</h2>
            </div>
            <div class="col-12">
                <a href="index.php">Home</a>
                <a href="about.php">About Us</a>
            </div>
        </div>
    </div>
</div>
<!-- Page Header End -->

<!-- About Section Start -->
<div class="about">
    <div class="container">
        <div class="row align-items-center">
            <!-- Image Column -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="about-img">
                    <img src="img/about.jpg" alt="About Image" class="img-fluid rounded shadow-sm">
                </div>
            </div>

            <!-- Content Column -->
            <div class="col-lg-6">
                <div class="section-header text-left">
                    <p>About Us</p>
                    <h2>Car Washing & Detailing</h2>
                </div>

                <div class="about-content">
                    <?php 
                    try {
                        $sql = "SELECT PageDescription FROM tblpages WHERE PageType = 'aboutus' LIMIT 1";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $result = $query->fetch(PDO::FETCH_OBJ);

                        if ($result && !empty(trim($result->PageDescription))) {
                            echo $result->PageDescription; // don't escape to allow HTML
                        } else {
                            echo '<p>We are dedicated to making your car sparkle inside and out. More information will be available soon.</p>';
                        }
                    } catch (Exception $e) {
                        echo '<p>Error loading content. Please try again later.</p>';
                    }
                    ?>

                    <hr />
                    <ul class="list-unstyled">
                        <li><i class="far fa-check-circle text-success"></i> Seats washing</li>
                        <li><i class="far fa-check-circle text-success"></i> Vacuum cleaning</li>
                        <li><i class="far fa-check-circle text-success"></i> Interior wet cleaning</li>
                        <li><i class="far fa-check-circle text-success"></i> Window wiping</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About Section End -->

<?php include_once('includes/footer.php'); ?>

<!-- JS Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/counterup/counterup.min.js"></script>

<!-- Main JS -->
<script src="js/main.js"></script>
</body>
</html>
