
<link href="css/style.css" rel="stylesheet">
<div class="footer">
    <div class="container">
        <div class="row">
            <!-- Contact Info -->
            <div class="col-lg-6 col-md-6">
                <div class="footer-contact">
                    <h2>Get In Touch</h2>
                    <?php 
                    $sql = "SELECT * FROM tblpages WHERE PageType = 'contactus'";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                    if ($query->rowCount() > 0):
                        foreach ($results as $result): ?>
                            <p><i class="fa fa-map-marker-alt"></i> <?= htmlentities($result->detail); ?></p>
                            <p><i class="fa fa-phone-alt"></i> +<?= htmlentities($result->phoneNumber); ?></p>
                            <p><i class="fa fa-envelope"></i> <?= htmlentities($result->emailId); ?></p>
                        <?php endforeach; 
                    else: ?>
                        <p>Contact information not available.</p>
                    <?php endif; ?>

                    <div class="footer-social">
                        <a class="btn" href="#"><i class="fab fa-twitter"></i></a>
                        <a class="btn" href="#"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn" href="#"><i class="fab fa-youtube"></i></a>
                        <a class="btn" href="#"><i class="fab fa-instagram"></i></a>
                        <a class="btn" href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>

            <!-- Links -->
            <div class="col-lg-5 col-md-6">
                <div class="footer-link">
                    <h2>Popular Links</h2>
                    <a href="index.php">Home</a>
                    <a href="about.php">About Us</a>
                    <a href="washing-plans.php">Washing Plans</a>
                    <a href="location.php">Washing Points</a>
                    <a href="contact.php">Contact Us</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="container copyright">
        <p>Car Wash Management System</p>
    </div>
</div>

<!-- Back to top button -->
<a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

<!-- Pre Loader -->
<div id="loader" class="show">
    <div class="loader"></div>
</div>

<script>
let lastUnread = 0;

function loadNotifications() {
  $.get('fetch_notifications.php', function(res) {
    try {
      const data = JSON.parse(res);
      const notifs = data.notifications || [];
      const unread = data.unread || 0;

      // badge
      $('#notifCount').text(unread > 0 ? unread : '');

      // list
      let html = '';
      if (notifs.length === 0) {
        html = '<p class="text-muted text-center mb-0">No notifications</p>';
      } else {
        notifs.forEach(n => {
          const cls = n.is_read == 0 ? 'font-weight-bold bg-light' : '';
          html += `<div class="dropdown-item ${cls}">
                     ${n.message}
                     <br><small class="text-muted">${n.created_at}</small>
                   </div>`;
        });
      }
      $('#notifList').html(html);

      // toast if new notification
      if (unread > lastUnread) {
        const latest = notifs[0] ? notifs[0].message : 'New notification';
        $('#toastMessage').text(latest);
        $('#liveToast').toast('show');
      }
      lastUnread = unread;
    } catch (e) {
      console.error('Invalid response', res);
    }
  });
}

// mark as read on dropdown open
$('#notifDropdown').on('show.bs.dropdown', function() {
  $.post('mark_notifications_read.php', function() {
    $('#notifCount').text('');
    lastUnread = 0;
  });
});

loadNotifications();
setInterval(loadNotifications, 8000);
</script>

<div class="toast" id="liveToast" style="position: fixed; bottom: 20px; right: 20px;" data-delay="5000">
  <div class="toast-header">
    <i class="fa fa-bell text-primary mr-2"></i>
    <strong class="mr-auto">Notification</strong>
    <small class="text-muted">now</small>
    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
  </div>
  <div class="toast-body" id="toastMessage"></div>
</div>
