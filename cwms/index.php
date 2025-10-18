<?php //error_reporting(0);
include('includes/config.php');

if (isset($_POST['book'])) {
    $ptype = $_POST['packagetype'];
    $wpoint = $_POST['washingpoint'];
    $fname = $_POST['fname'];
    $mobile = $_POST['contactno'];
    $date = $_POST['washdate'];
    $time = $_POST['washtime'];
    $message = $_POST['message'];
    $status = 'New';
    $bno = mt_rand(100000000, 999999999);
    $sql = "INSERT INTO tblcarwashbooking(bookingId,packageType,carWashPoint,fullName,mobileNumber,washDate,washTime,message,status) VALUES(:bno,:ptype,:wpoint,:fname,:mobile,:date,:time,:message,:status)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bno', $bno, PDO::PARAM_STR);
    $query->bindParam(':ptype', $ptype, PDO::PARAM_STR);
    $query->bindParam(':wpoint', $wpoint, PDO::PARAM_STR);
    $query->bindParam(':fname', $fname, PDO::PARAM_STR);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->bindParam(':date', $date, PDO::PARAM_STR);
    $query->bindParam(':time', $time, PDO::PARAM_STR);
    $query->bindParam(':message', $message, PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if ($lastInsertId) {

        echo '<script>alert("Your booking done successfully. Booking number is "+"' . $bno . '")</script>';
        echo "<script>window.location.href ='washing-plans.php'</script>";
    } else {
        echo "<script>alert('Something went wrong. Please try again.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Car Wash management System | Home Page</title>


    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Font -->
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

    <!-- Carousel Start -->
    <div class="carousel">
        <div class="container-fluid">
            <div class="owl-carousel">
                <div class="carousel-item">
                    <div class="carousel-img">
                        <img src="img/carousel-1.jpg" alt="Image">
                    </div>
                    <div class="carousel-text">
                        <h3>Washing & Detailing</h3>
                        <h1>Keep your Car Newer</h1>

                    </div>
                </div>
                <div class="carousel-item">
                    <div class="carousel-img">
                        <img src="img/carousel-2.jpg" alt="Image">
                    </div>
                    <div class="carousel-text">
                        <h3>Washing & Detailing</h3>
                        <h1>Quality service for you</h1>

                    </div>
                </div>
                <div class="carousel-item">
                    <div class="carousel-img">
                        <img src="img/carousel-3.jpg" alt="Image">
                    </div>
                    <div class="carousel-text">
                        <h3>Washing & Detailing</h3>
                        <h1>Exterior & Interior Washing</h1>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->


    <!-- About Start -->
    <div class="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="about-img">
                        <img src="img/about.jpg" alt="Image">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="section-header text-left">
                        <p>About Us</p>
                        <h2>car washing and detailing</h2>
                    </div>
                    <div class="about-content">
                        <p>
                            At Drip Labs, we make cars shine inside and out. From quick washes to full detailing, our team uses eco-friendly products and modern tools to give your car the care it deserves. Fast, reliable, and affordable – because your car deserves the best!
                        <ul>
                            <li><i class="far fa-check-circle"></i>Seats washing</li>
                            <li><i class="far fa-check-circle"></i>Vacuum cleaning</li>
                            <li><i class="far fa-check-circle"></i>Interior wet cleaning</li>
                            <li><i class="far fa-check-circle"></i>Window wiping</li>
                        </ul>
                        <a class="btn btn-custom" href="about.php">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


    <!-- Service Start -->
    <div class="service">
        <div class="container">
            <div class="section-header text-center">
                <p>What We Do?</p>
                <h2>Premium Washing Services</h2>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="service-item">
                        <i class="flaticon-car-wash-1"></i>
                        <h3>Exterior Washing</h3>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="service-item">
                        <i class="flaticon-car-wash"></i>
                        <h3>Interior Washing</h3>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="service-item">
                        <i class="flaticon-vacuum-cleaner"></i>
                        <h3>Vacuum Cleaning</h3>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="service-item">
                        <i class="flaticon-seat"></i>
                        <h3>Seats Washing</h3>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="service-item">
                        <i class="flaticon-car-service"></i>
                        <h3>Window Wiping</h3>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="service-item">
                        <i class="flaticon-car-service-2"></i>
                        <h3>Wet Cleaning</h3>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="service-item">
                        <i class="flaticon-car-wash"></i>
                        <h3>Oil Changing</h3>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="service-item">
                        <i class="flaticon-brush-1"></i>
                        <h3>Brake Reparing</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Service End -->


    <!-- Facts Start -->
    <div class="facts" data-parallax="scroll" data-image-src="img/facts.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="facts-item">
                        <i class="fa fa-map-marker-alt"></i>
                        <div class="facts-text">
                            <h3 data-toggle="counter-up">25</h3>
                            <p>Service Points</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="facts-item">
                        <i class="fa fa-user"></i>
                        <div class="facts-text">
                            <h3 data-toggle="counter-up">350</h3>
                            <p>Engineers & Workers</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="facts-item">
                        <i class="fa fa-users"></i>
                        <div class="facts-text">
                            <h3 data-toggle="counter-up">1500</h3>
                            <p>Happy Clients</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="facts-item">
                        <i class="fa fa-check"></i>
                        <div class="facts-text">
                            <h3 data-toggle="counter-up">5000</h3>
                            <p>Projects Completed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Facts End -->


    <!-- Price Start -->
    <div class="price">
        <div class="container">
            <div class="section-header text-center">
                <p>Washing Plan</p>
                <h2>Choose Your Plan</h2>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="price-item">
                        <div class="price-header">
                            <h3>Basic Wash</h3>
                            <h2><span>₹</span><strong>1000</strong><span></span></h2>
                        </div>
                        <div class="price-body">
                            <ul>
                                <li><i class="far fa-check-circle"></i>Exterior Cleaning</li>
                                <li><i class="far fa-check-circle"></i>Vacuuming</li>
                                <li><i class="far fa-times-circle"></i>Interior Detailing</li>
                            </ul>
                        </div>
                        <div class="price-footer">
                            <a class="btn btn-custom" href="booking.php?plan=Basic Wash">Book Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="price-item featured-item">
                        <div class="price-header">
                            <h3>Premium Wash</h3>
                            <h2><span>₹</span><strong>1800</strong><span></span></h2>
                        </div>
                        <div class="price-body">
                            <ul>
                                <li><i class="far fa-check-circle"></i>Exterior & Interior</li>
                                <li><i class="far fa-check-circle"></i>Wax Polish</li>
                                <li><i class="far fa-check-circle"></i>Vacuum Cleaning</li>
                            </ul>
                        </div>
                        <div class="price-footer">
                            <a class="btn btn-custom" href="booking.php?plan=Premium Wash">Book Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="price-item">
                        <div class="price-header">
                            <h3>Interior Detailing</h3>
                            <h2><span>₹</span><strong>2700</strong><span></span></h2>
                        </div>
                        <div class="price-body">
                            <ul>
                                <li><i class="far fa-check-circle"></i>Deep Interior Cleaning</li>
                                <li><i class="far fa-check-circle"></i>Stain Removal</li>
                                <li><i class="far fa-check-circle"></i>Leather Polish</li>
                            </ul>
                        </div>
                        <div class="price-footer">
                            <a class="btn btn-custom" href="booking.php?plan=Interior Detailing">Book Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Price End -->




    <!-- Footer Start -->
    <?php include_once('includes/footer.php'); ?>

    


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>