<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About us</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    /* Hero section styling */
    .hero {
      background: url('img/bg.png') no-repeat center center/cover;
      position: relative;
      color: white;
      padding: 100px 0;
    }

    .hero::before {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      z-index: 0;
    }

    .hero-content {
      position: relative;
      z-index: 1;
    }

    .hero img {
      max-width: 100%;
    }
    
    .contact-info {
            margin-top: 20px;
        }
        .map {
            margin-top: 20px;
           
        }
        .footer a:hover {
        color: #3B9797 !important;
            text-decoration: underline;
        }

        .navbar-custom {
            background-color: #132440 !important;
        }
        .navbar-custom .nav-link,
        .navbar-custom .navbar-brand {
            color: #fff !important;
        }
        .blue-text{
            color: #334D94;
        }
        .text-dark{
            background-color: #e9ecf5;
        }
        .btn-orange {
  background-color: #3B9797;
  border: none;
  padding: 6px 16px;
  font-weight: 600;
  border-radius: 8px;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn-orange:hover {
  background-color: #018790;
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
  
}
    .navbar-custom {
  background-color: #132440 !important;
}

/* Transparent logo */
.logo-img {
  width: 70px;
  height: auto;
  object-fit: contain;
  background-color: transparent;
}

/* Remove link color overrides, use default Bootstrap for responsiveness */
.navbar-custom .nav-link {
  color: #fff !important;
  margin-left: 10px;
}

.navbar-custom .nav-link:hover {
  text-decoration: underline;
}

/* Optional: orange button style */
.btn-orange {
  background-color: #3B9797;
  border: none;
  padding: 6px 16px;
  font-weight: 600;
  border-radius: 8px;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn-orange:hover {
  background-color: #018790;
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
  
}
/* Change the color of the navbar toggler (hamburger icon) */
.navbar-light .navbar-toggler-icon {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='white' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
}

/* Optional: Change the border of the toggler button */
.navbar-light .navbar-toggler {
  border-color: rgba(255, 255, 255, 0.5); /* White border */
}




  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-md navbar-light navbar-custom shadow-sm">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="img/logo.png" alt="Logo" class="logo-img me-2">
      <div class="brand-text">
        <span class="d-block text-white fw-bold">BARANGAY</span>
        <span class="d-block text-white small">INFORMATION MANAGEMENT SYSTEM</span>
      </div>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link fw-semibold text-white" href="homepage.php">HOME</a></li>
        <li class="nav-item"><a class="nav-link fw-semibold text-white" href="about.php">ABOUT US</a></li>
        <li class="nav-item"><a class="nav-link fw-semibold text-white" href="contactUs.php">CONTACT US</a></li>
        <li class="nav-item ms-md-3 mt-2 mt-md-0">
          <a class="btn btn-orange text-white" href="#">LOGIN</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- Hero Section -->
<!-- Contact Us Section -->
<section class="hero d-flex align-items-center justify-content-center   ">
    <div class="container text-center">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-white">
          <h1 class="display-5 fw-bold">Contact Us</h1>
          <p class="lead mt-3">
            <strong>Reach Out and Let's Transform Barangay Governance Together</strong>
          </p>
        </div>
      </div>
    </div>
  </section>
  

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 contact-info">
                <h2 class="blue-text">Thank you for your interest in the Barangay Information Management System (BIMS)</h2>
                <p>If you have any questions, feedback, or inquiries, please don’t hesitate to reach out to us. Our dedicated team is here to assist you and provide the information you need.</p>
    
                <h4 class="blue-text">Contact Information:</h4>
                <p><strong class="blue-text">Address:</strong><br>
                Barangay 468, Sampaloc, Manila, Metro Manila</p>
                <p><strong class="blue-text">Phone Numbers:</strong><br>
                0286823982<br></p>
                <p><strong class="blue-text">Email:</strong><br>
                onward468z46@gmail.com</p>
                <p><strong class="blue-text">Office Hours:</strong><br>
                Monday – Friday: 8:00 AM – 5:00 PM</p>
            </div>
    
            <div class="col-md-6 map">
              <iframe src="https://www.google.com/maps/embed?pb=!4v1767610789062!6m8!1m7!1s2uM77A1pkY5pbd6k33sd4A!2m2!1d14.6070647755383!2d120.987571768974!3f147.17724253750146!4f-13.59450317540535!5f0.7820865974627469" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
  </iframe>          </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

    <footer class="footer text-white pt-5 pb-4 mt-5" style="background-color: #132440;">
  <div class="container text-md-left">
    <div class="row text-md-left">

      <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
        <h5 class="text-uppercase mb-4 font-weight-bold">Barangay System</h5>
        <p>Empowering communities with efficient and transparent digital services for better governance and connection.</p>
      </div>

      <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
        <h5 class="text-uppercase mb-4 font-weight-bold">Quick Links</h5>
        <p><a href="index.php" class="text-white" style="text-decoration: none;">Home</a></p>
        <p><a href="about.php" class="text-white" style="text-decoration: none;">About Us</a></p>
        <p><a href="contactUs.php" class="text-white" style="text-decoration: none;">Contact</a></p>
      </div>

      <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
        <h5 class="text-uppercase mb-4 font-weight-bold">Contact</h5>
        <p><i class="fas fa-home me-2"></i> Barangay 468, Sampaloc, Manila, Metro Manila</p>
        <p><i class="fas fa-envelope me-2"></i> onward468z46@gmail.com</p>
        <p><i class="fas fa-phone me-2"></i> 0286823982</p>
      </div>

    </div>

    <hr class="mb-4">

    <div class="row align-items-center">
      <div class="col-md-7 col-lg-8">
        <p>© 2025 Barangay 468 Infomation Management System. All Rights Reserved.</p>
      </div>

      <div class="col-md-5 col-lg-4">
        <div class="text-center text-md-end">
          <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
          <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
        </div>
      </div>
    </div>
  </div>
</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

