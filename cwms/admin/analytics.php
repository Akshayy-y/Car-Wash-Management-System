<?php
session_start();
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}

// Fetch stats
// Today & This Month bookings
$today = date('Y-m-d');
$thisMonthStart = date('Y-m-01');
$sqlStats = "
SELECT 
  SUM(status = 'Completed') AS completedCount,
  SUM(status = 'New') AS newCount,
  SUM(status = 'Cancelled') AS cancelledCount,
  SUM(CASE WHEN DATE(BookingDate)=:today THEN 1 ELSE 0 END) AS todayCount,
  SUM(CASE WHEN DATE(BookingDate) BETWEEN :monthStart AND :today THEN 1 ELSE 0 END) AS monthCount,
  SUM(CASE WHEN status='Completed' THEN amount ELSE 0 END) AS totalRevenue
FROM tblbooking";
$q = $dbh->prepare($sqlStats);
$q->bindParam(':today', $today);
$q->bindParam(':monthStart', $thisMonthStart);
$q->execute();
$stats = $q->fetch(PDO::FETCH_ASSOC);

// Upcoming 5 appointments
$sqlNext = "SELECT id, FullName, WashDate, WashTime 
            FROM tblbooking 
            WHERE status='New' AND WashDate>=:today 
            ORDER BY WashDate, WashTime LIMIT 5";
$q2 = $dbh->prepare($sqlNext);
$q2->bindParam(':today', $today);
$q2->execute();
$upcoming = $q2->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Admin Analytics | CWMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/morris.css" type="text/css" />
    <link href="css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/table-style.css" />
    <link rel="stylesheet" type="text/css" href="css/basictable.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-card {
            transition: transform .2s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body>
    <div class="page-container">
        <?php include('includes/sidebarmenu.php'); ?>
        <div class="left-content">
            <?php include('includes/header.php'); ?>
            <div class="container-fluid mt-4">
                <h3>📊 Admin Dashboard Analytics</h3>
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card shadow-sm">
                            <div class="card-body">
                                <h5>Today</h5>
                                <h2><?= $stats['todayCount'] ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card shadow-sm">
                            <div class="card-body">
                                <h5>This Month</h5>
                                <h2><?= $stats['monthCount'] ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card shadow-sm">
                            <div class="card-body">
                                <h5>Revenue (INR)</h5>
                                <h2>₹<?= number_format($stats['totalRevenue'], 2) ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5>Status Breakdown</h5>
                                <canvas id="statusChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5>Next 5 Upcoming Appointments</h5>
                                <?php if (count($upcoming)): ?>
                                    <ul class="list-group">
                                        <?php foreach ($upcoming as $u): ?>
                                            <li class="list-group-item">
                                                <strong>#<?= htmlentities($u['id']) ?></strong>—
                                                <?= htmlentities($u['FullName']) ?> on
                                                <?= htmlentities($u['WashDate']) ?> at
                                                <?= htmlentities($u['WashTime']) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-muted">No upcoming appointments.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('statusChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['New', 'Completed', 'Cancelled'],
                datasets: [{
                    label: '# of Bookings',
                    data: [
                        <?= $stats['newCount'] ?>,
                        <?= $stats['completedCount'] ?>,
                        <?= $stats['cancelledCount'] ?>
                    ],
                    backgroundColor: ['#ffc107', '#28a745', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            }
        });
    </script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/jquery.basictable.min.js"></script> 
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    
</body>

</html>