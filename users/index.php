<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Get filters from URL
$type = isset($_GET['type']) ? trim($_GET['type']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';

// Define base URL for VS Code PHP Server, including user/ subdirectory
define('BASE_URL', 'http://localhost:3000/');

// Fetch properties
$properties = getProperties($conn, $type, $status);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Dream Homes</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,900|Playfair+Display:400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/jquery-ui.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/owl.carousel.min.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/owl.theme.default.min.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/jquery.fancybox.min.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/bootstrap-datepicker.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../fonts/icomoon/style.css?v=<?= time() ?>">
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

    <div class="site-blocks-cover overlay" style="background-image: url(../images/hero_1.jpg);" data-aos="fade" id="home-section">
      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-md-6 mt-lg-5 text-center">
            <h1>Find Your Dream Property</h1>
            <p class="mb-5">Explore our curated selection of properties to find the perfect home or investment opportunity.</p>
          </div>
        </div>
      </div>
      <a href="#howitworks-section" class="smoothscroll arrow-down"><span class="icon-arrow_downward"></span></a>
    </div>  

    <div class="py-5 bg-light site-section how-it-works" id="howitworks-section">
      <div class="container">
        <div class="row mb-5 justify-content-center">
          <div class="col-md-7 text-center">
            <h2 class="section-title mb-3">How It Works</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 text-center">
            <div class="pr-5">
              <span class="custom-icon flaticon-house text-primary"></span>
              <h3 class="text-dark">Find Property.</h3>
              <p>Use our filters to discover properties that match your needs.</p>
            </div>
          </div>
          <div class="col-md-4 text-center">
            <div class="pr-5">
              <span class="custom-icon flaticon-coin text-primary"></span>
              <h3 class="text-dark">Buy Property.</h3>
              <p>Contact our agents to secure your dream property.</p>
            </div>
          </div>
          <div class="col-md-4 text-center">
            <div class="pr-5">
              <span class="custom-icon flaticon-home text-primary"></span>
              <h3 class="text-dark">Make Investment.</h3>
              <p>Invest in real estate with confidence.</p>
            </div>
          </div>
        </div>
      </div>  
    </div>

    <div class="site-section" id="properties-section">
      <div class="container">
        <div class="row mb-5 align-items-center">
          <div class="col-md-7 text-left">
            <h2 class="section-title mb-3">Properties</h2>
          </div>
          <div class="col-md-5 text-left text-md-right">
            <div class="custom-nav1">
              <a href="#" class="custom-prev1">Previous</a><span class="mx-3">/</span><a href="#" class="custom-next1">Next</a>
            </div>
          </div>
        </div>

        <!-- Filter Form -->
        <div class="filter-container mb-4">
          <form method="GET" class="row">
            <div class="col-md-4 mb-3">
              <select name="type" class="form-control">
                <option value="">All Types</option>
                <option value="House" <?= $type === "House" ? "selected" : "" ?>>House</option>
                <option value="Land" <?= $type === "Land" ? "selected" : "" ?>>Land</option>
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <select name="status" class="form-control">
                <option value="">All Status</option>
                <option value="For Sale" <?= $status === "For Sale" ? "selected" : "" ?>>For Sale</option>
                <option value="For Rent" <?= $status === "For Rent" ? "selected" : "" ?>>For Rent</option>
                <option value="Sold" <?= $status === "Sold" ? "selected" : "" ?>>Sold</option>
                <option value="Rented" <?= $status === "Rented" ? "selected" : "" ?>>Rented</option>
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <button class="btn btn-primary w-100">Filter</button>
            </div>
          </form>
        </div>

        <!-- Property Cards in Owl Carousel -->
        <div class="owl-carousel nonloop-block-13 mb-5">
          <?php if ($properties && $properties->num_rows > 0): ?>
            <?php while ($row = $properties->fetch_assoc()): ?>
              <?php
                $randomImage = getRandomPropertyImage($conn, $row['id']);
                $imagePath = $randomImage ? BASE_URL . 'Uploads/' . htmlspecialchars($randomImage) : BASE_URL . 'assets/no-image.jpg';
              ?>
              <div class="property" data-id="<?= $row['id'] ?>">
                <a href="property.php?id=<?= $row['id'] ?>">
                  <img src="<?= $imagePath ?>" alt="Property Image" class="img-fluid">
                </a>
                <div class="prop-details p-3">
                  <div><strong class="price">PKR <?= number_format($row['price'], 2) ?></strong></div>
                  <div class="mb-2 d-flex justify-content-between">
                    <span class="w border-r"><?= htmlspecialchars($row['type']) ?></span> 
                    <span class="w border-r"><?= htmlspecialchars($row['status']) ?></span>
                    <span class="w"><?= date("M d, Y", strtotime($row['created_at'])) ?></span>
                  </div>
                  <div><?= htmlspecialchars($row['title']) ?></div>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="col-12 text-center py-6">
              <img src="<?= BASE_URL ?>assets/no-results.svg" alt="No results" class="mb-3 w-24 mx-auto">
              <p class="text-dark">No properties match your search.</p>
            </div>
          <?php endif; ?>
        </div>
        <div class="row justify-content-center">
          <div class="col-md-4">
            <a href="property.php" class="btn btn-primary btn-block">View All Property Listings</a>
          </div>
        </div>
      </div>
    </div>

    <section class="site-section" id="about-section">
      <div class="container">
        <div class="row">
          <div class="col-lg-6">
            <div class="owl-carousel slide-one-item-alt">
              <img src="../images/property_1.jpg" alt="Image" class="img-fluid">
              <img src="../images/property_2.jpg" alt="Image" class="img-fluid">
              <img src="../images/property_3.jpg" alt="Image" class="img-fluid">
              <img src="../images/property_4.jpg" alt="Image" class="img-fluid">
            </div>
            <div class="custom-direction">
              <a href="#" class="custom-prev">Prev</a><a href="#" class="custom-next">Next</a>
            </div>
          </div>
          <div class="col-lg-5 ml-auto">
            <h2 class="section-title mb-3">We Are The Best RealEstate Company</h2>
            <p class="lead">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            <p>Est qui eos quasi ratione nostrum excepturi id recusandae fugit omnis ullam pariatur itaque nisi voluptas impedit Quo suscipit omnis iste velit maxime.</p>
            <ul class="list-unstyled ul-check success">
              <li>Placeat maxime animi minus</li>
              <li>Dolore qui placeat maxime</li>
              <li>Consectetur adipisicing</li>
              <li>Lorem ipsum dolor</li>
              <li>Placeat molestias animi</li>
            </ul>
            <p><a href="#" class="btn btn-primary mr-2 mb-2">Learn More</a></p>
          </div>
        </div>
      </div>
    </section>

    <section class="site-section border-bottom bg-light" id="services-section">
      <div class="container">
        <div class="row mb-5">
          <div class="col-12 text-center">
            <h2 class="section-title mb-3">Services</h2>
          </div>
        </div>
        <div class="row align-items-stretch">
          <div class="col-md-6 col-lg-4 mb-4 mb-lg-4" data-aos="fade-up">
            <div class="unit-4 d-flex">
              <div class="unit-4-icon mr-4"><span class="text-primary flaticon-house"></span></div>
              <div>
                <h3>Search Property</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perferendis quis molestiae vitae eligendi at.</p>
                <p><a href="#">Learn More</a></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer class="site-footer">
      <div class="container">
        <div class="row">
          <div class="col-md-8">
            <div class="row">
              <div class="col-md-5">
                <h2 class="footer-heading mb-4">About Dream Homes</h2>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque facere laudantium magnam voluptatum autem. Amet aliquid nesciunt veritatis aliquam.</p>
              </div>
              <div class="col-md-3 ml-auto">
                <h2 class="footer-heading mb-4">Quick Links</h2>
                <ul class="list-unstyled">
                  <li><a href="#about-section">About Us</a></li>
                  <li><a href="#services-section">Services</a></li>
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
                Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved</p>
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