<?php
session_start();
include('includes/config.php');

if (isset($_POST['submitReview']) && isset($_SESSION['userlogin'])) {
    $userId = $_SESSION['userlogin'];
    $bookingId = $_POST['bookingId'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    $sql = "INSERT INTO tblreviews (userId, bookingId, rating, review) VALUES (:uid, :bid, :rating, :review)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':uid', $userId);
    $query->bindParam(':bid', $bookingId);
    $query->bindParam(':rating', $rating);
    $query->bindParam(':review', $review);
    $query->execute();

    echo "<script>alert('Review submitted successfully!'); window.location='my-bookings.php';</script>";
} else {
    echo "<script>alert('Invalid action.'); window.location='login.php';</script>";
}
