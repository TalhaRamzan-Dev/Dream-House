<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Validate property ID from query parameter
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='container py-5'><p>Property not found.</p></div>";
    exit;
}

$id = (int)$_GET['id'];

// Fetch property details
$property = getPropertyById($conn, $id);
if (!$property) {
    echo "<div class='container py-5'><p>Property not found.</p></div>";
    exit;
}

// Fetch all images for this property
$images = getPropertyImages($conn, $id);

// Prepare WhatsApp URL
$message = "Hello, I'm interested in the property titled '" . $property['title'] . "'. Could you please provide more details?";
$whatsappUrl = "https://wa.me/" . BUSINESS_WHATSAPP_NUMBER . "?text=" . urlencode($message);

// Define base URL, including user/ subdirectory
define('BASE_URL', 'http://localhost:3000/');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Stated &mdash; Property Details</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,900|Playfair+Display:400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="../fonts/icomoon/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/bootstrap.min.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/jquery-ui.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/owl.carousel.min.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/owl.theme.default.min.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/jquery.fancybox.min.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/bootstrap-datepicker.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../fonts/flaticon/font/flaticon.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/aos.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/style.css?v=<?= time() ?>">
  </head>
  <body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">
  
  <div class="site-wrap">

    <div class="site-mobile-menu site-navbar-target">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
          <span class="icon-close2 js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div>
   
   <header class="site-navbar py-4 js-sticky-header site-navbar-target" role="banner">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-6 col-xl-2">
        <h1 class="mb-0 site-logo m-0 p-0"><a href="index.php" class="mb-0 text-nowrap">Dream Homes</a></h1>
      </div>
      <div class="col-12 col-md-10 d-none d-xl-block">
        <nav class="site-navigation position-relative text-right" role="navigation">
          <ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-block">
            <li><a href="#home-section" class="nav-link">Home</a></li>
            <li><a href="#properties-section" class="nav-link">Properties</a></li>
            <li><a href="#about-section" class="nav-link">About</a></li>
          </ul>
        </nav>
      </div>
      <div class="col-6 d-inline-block d-xl-none ml-md-0 py-3">
        <a href="#" class="site-menu-toggle js-menu-toggle text-black float-right"><span class="icon-menu h3"></span></a>
      </div>
    </div>
  </div>
</header>

    <div class="site-section">
      <div class="container">
        <h1 class="section-title mb-4"><?= htmlspecialchars($property['title']) ?></h1>
        <div class="row">
          <div class="col-md-8">
            <?php if (!empty($images)): ?>
              <div class="owl-carousel slide-one-item-alt mb-5">
                <?php foreach ($images as $img): ?>
                  <img src="<?= BASE_URL ?>Uploads/<?= htmlspecialchars($img) ?>" alt="Property Image" class="img-fluid" style="max-height: 500px; object-fit: cover;">
                <?php endforeach; ?>
              </div>
              <div class="custom-direction">
                <a href="#" class="custom-prev">Prev</a><a href="#" class="custom-next">Next</a>
              </div>
            <?php else: ?>
              <img src="<?= BASE_URL ?>assets/no-image.jpg" alt="No Image Available" class="img-fluid mb-5" style="max-height: 500px; object-fit: cover;">
            <?php endif; ?>

            <div class="prop-details p-3">
              <div><strong class="price">PKR <?= number_format($property['price'], 2) ?></strong></div>
              <div class="mb-2 d-flex justify-content-between">
                <span class="w border-r"><?= htmlspecialchars($property['type']) ?></span> 
                <span class="w border-r"><?= htmlspecialchars($property['status']) ?></span>
                <span class="w"><?= date("M d, Y", strtotime($property['created_at'])) ?></span>
              </div>
              <p><?= nl2br(htmlspecialchars($property['description'])) ?></p>
              <a href="<?= $whatsappUrl ?>" target="_blank" class="btn btn-primary btn-lg mb-3">
                <span class="icon-whatsapp"></span> Contact via WhatsApp
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <footer class="site-footer">
      <div class="container">
        <div class="row">
          <div class="col-md-8">
            <div class="row">
              <div class="col-md-5">
                <h2 class="footer-heading mb-4">About Stated</h2>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque facere laudantium magnam voluptatum autem. Amet aliquid nesciunt veritatis aliquam.</p>
              </div>
              <div class="col-md-3 ml-auto">
                <h2 class="footer-heading mb-4">Quick Links</h2>
                <ul class="list-unstyled">
                  <li><a href="index.php#about-section">About Us</a></li>
                  <li><a href="index.php#services-section">Services</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="">
              <h2 class="footer-heading mb-4">Follow Us</h2>
              <a href="#" class="pl-0 pr-3"><span class="icon-facebook"></span></a>
              <a href="#" class="pl-3 pr-3"><span class="icon-twitter"></span></a>
              <a href="#" class="pl-3 pr-3"><span class="icon-instagram"></span></a>
              <a href="#" class="pl-3 pr-3"><span class="icon-linkedin"></span></a>
            </div>
          </div>
        </div>
        <div class="row pt-5 mt-5 text-center">
          <div class="col-md-12">
            <div class="border-top pt-5">
              <p>
                Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </footer>

  </div> <!-- .site-wrap -->

  <script src="../js/jquery-3.3.1.min.js?v=<?= time() ?>"></script>
  <script src="../js/jquery-migrate-3.0.1.min.js?v=<?= time() ?>"></script>
  <script src="../js/jquery-ui.js?v=<?= time() ?>"></script>
  <script src="../js/popper.min.js?v=<?= time() ?>"></script>
  <script src="../js/bootstrap.min.js?v=<?= time() ?>"></script>
  <script src="../js/owl.carousel.min.js?v=<?= time() ?>"></script>
  <script src="../js/jquery.stellar.min.js?v=<?= time() ?>"></script>
  <script src="../js/jquery.countdown.min.js?v=<?= time() ?>"></script>
  <script src="../js/bootstrap-datepicker.min.js?v=<?= time() ?>"></script>
  <script src="../js/jquery.easing.1.3.js?v=<?= time() ?>"></script>
  <script src="../js/aos.js?v=<?= time() ?>"></script>
  <script src="../js/jquery.fancybox.min.js?v=<?= time() ?>"></script>
  <script src="../js/jquery.sticky.js?v=<?= time() ?>"></script>
  <script src="../js/main.js?v=<?= time() ?>"></script>
    
  </body>
</html>
<?php $conn->close(); ?>