<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Barangay Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <link rel="icon" href="favicon.ico" type="image/x-icon">

  <style>
    
    @media screen and (max-width: 768px) {
    section {
      flex-direction: column;
      text-align: center;
    }
    section div {
      max-width: 100%;
    }
    section img {
      width: 100%;
      margin-bottom: 20px;
    }
    section div p {
      max-width: 100%;
    }
  }

  /* Hero section styling */
  .hero {
    background: url('img/bg.png') no-repeat center center/cover;
    position: relative;
    color: #e9ecf5;
    height: 100vh; /* Takes up the entire screen height */
  }

  .hero::before {
    content: "";
    position: absolute;
    top: 0; left: 0;
    width: 100%; 
    height: 100%;
    z-index: 0;
    pointer-events: none;
  }

  .hero-content {
    padding-top: 200px; /* move text down */
    padding-bottom: 50px; /* add space below */
  }

  .hero img {
    width: 500px;
    height: 300px;
  }


  section {
    font-family: 'Segoe UI', sans-serif;
  }

  section h2 {
    font-size: 1.5em;
    font-weight: bold;
  }

  section div p {
    font-size: 0.95em;
    color: #333;
  }

  .blue-text {
    color: #334D94;
  }

  .text-white {
    color: white;
  }

  .report-summary {
    background-color: #ffffff;
  }

  .report-summary h2 {
    color: #334D94;
  }

  .report-summary p {
    font-size: 1rem;
  }

  .footer a:hover {
    color: #3B9797 !important;
    text-decoration: underline;
  }

  .text-dark {
    background-color: #132440;
    font: #ffffff;
  }

  /* Navbar background */
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
        <li class="nav-item"><a class="nav-link fw-semibold text-white" href="about.php">ABOUT US</a></li>
        <li class="nav-item"><a class="nav-link fw-semibold text-white" href="contactUs.php">CONTACT US</a></li>
        <li class="nav-item ms-md-3 mt-2 mt-md-0">
          <a class="btn btn-orange text-white" href="residents/login.php">LOGIN</a>
        </li>
      </ul>
    </div>
  </div>
</nav>


  <!-- Hero Section -->
  <section class="hero">
    <div class="container hero-content">
      <div class="row align-items-center">
        <div class="col-lg-6 text-white">
          <h1 class="display-5 fw-bold  " >Barangay Information Management System</h1>
          <p class="lead text-white">
            <strong class="text-white">Barangay Infomation Management System</strong> Makes Things Easier, Connects Everyone,<br />
            and Helps Your Community Succeed.
          </p>
          <a href="residents/login.php" class="btn btn-orange text-white btn-lg mt-3">LOGIN</a>
        </div>  
      </div>
    </div>
  </section>

  <!-- Introduction Section with Image and Text -->
<section style="display: flex; align-items: center; justify-content: center; gap: 40px; padding: 60px 20px; background-color: #f8f9fc; flex-wrap: wrap;">
  <div style="flex: 1; max-width: 500px;">
    <img src="img/overview.png" alt="Barangay System Overview" style="width: 100%; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
  </div>
  <div style="flex: 1; max-width: 500px;">
    <h2 style="color: #334D94; margin-bottom: 20px;">Empowering Communities Through Technology</h2>
    <p style="font-size: 1em; color: #444; line-height: 1.6;">
      The Barangay Infomation Management System helps streamline processes, reduce paperwork, and improve communication between residents and officials.
      With real-time data access, organized records, and digital services, the system boosts efficiency and promotes transparency at the grassroots level.
    </p>
  </div>
</section>

  <!-- Key Components Section -->
<section style="background-color: #e9ecf5; padding: 40px 20px; text-align: center;">
  <h2 style="color: #334D94; margin-bottom: 30px;">
    Barangay Information Management System Consists of Several Key Components and Initiatives
  </h2>
  <div style="display: flex; justify-content: center; gap: 40px; flex-wrap: wrap;">
    <div style="width: 200px;">
      <img src="img/dashboard.png" alt="Component 1 Icon" style="width: 60px; height: 60px;">
      <p style="margin-top: 10px;">Dashboard and Records Management</p>
    </div>
    <div style="width: 200px;">
      <img src="img/residents.png" alt="Component 2 Icon" style="width: 60px; height: 60px;">
      <p style="margin-top: 10px;">Resident Information & Services</p>
    </div>
    <div style="width: 200px;">
      <img src="img/community.png" alt="Component 3 Icon" style="width: 60px; height: 60px;">
      <p style="margin-top: 10px;">Community Engagement Tools</p>
    </div>
    <div style="width: 200px;">
      <img src="img/reports.png" alt="Component 4 Icon" style="width: 60px; height: 60px;">
      <p style="margin-top: 10px;">Reports & Analytics</p>
    </div>
  </div>
</section>

<!-- Key Features Section -->
<section style="text-align: center; padding: 60px 20px; background-color: #ffffff;">
  <h2 style="color: #334D94; font-size: 2em; margin-bottom: 10px;">Our Services</h2>
  <p style="font-size: 1em; color: #555; max-width: 700px; margin: 0 auto;">
    Our Barangay Information Management System offers fast and convenient services like incident reporting, certificate issuance, business permit processing, and barangay clearance requests—all done digitally to save time and reduce paperwork.
  </p>
</section>

<!-- Section: Image Left, Text Right -->
<section style="display: flex; align-items: center; justify-content: center; gap: 40px; padding: 60px 20px; background-color: #e9ecf5; flex-wrap: wrap;">
  <div style="flex: 1; max-width: 500px;">
    <img src="img/certificate.png" alt="Resident Records" style="width: 100%; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
  </div>
  <div style="flex: 1; max-width: 500px;">
    <h3 style="color: #334D94; margin-bottom: 15px;">Certificate Request</h3>
    <p style="font-size: 1em; color: #444; line-height: 1.6;">
      The Certificate Request facility in Barangay Information Management System (BIMS) enables citizens to apply effortlessly for different certificates like residency certificates, clearance certificates. Citizens are enabled to put forth their application for the requested certificates online from their platform through necessary information and attached documents. Request is approved and processed by barangay officers; they print and issue a digitally generated certificate. Residents can subsequently download the certificate or choose to retrieve a printed copy at the barangay office. This electronic system simplifies the request process, minimizing paperwork and waiting time, and making for efficient and transparent delivery of services to the people.
    </p>
  </div>
</section>

<!-- Section: Image Right, Text Left -->
<section style="display: flex; align-items: center; justify-content: center; gap: 40px; padding: 60px 20px; background-color: #ffffff; flex-wrap: wrap;">
  <div style="flex: 1; max-width: 500px; order: 2;">
    <img src="img/clearance.png" alt="Digital Services" style="width: 100%; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
  </div>
  <div style="flex: 1; max-width: 500px; order: 1;">
    <h3 style="color: #334D94; margin-bottom: 15px;">Barangay Clearnce</h3>
    <p style="font-size: 1em; color: #444; line-height: 1.6;">
      The Barangay Clearance module of the Barangay Information Management System (BIMS) allows residents to apply and secure barangay clearances promptly and easily. Residents can apply online by filling out their personal details and purpose of the clearance using the BMS platform. Barangay officials process the application and issue the clearance electronically upon verification. Residents can then opt to download the clearance or collect a printed copy at the barangay office. This process is simplified to decrease wait time, reduce paperwork, and enhance effective and clear delivery of services to the community and barangay staff.
    </p>
  </div>
</section>

<!-- Section: Image Left, Text Right -->
<section style="display: flex; align-items: center; justify-content: center; gap: 40px; padding: 60px 20px; background-color: #e9ecf5; flex-wrap: wrap;">
  <div style="flex: 1; max-width: 500px;">
    <img src="image/busin.png" alt="Efficient Services" style="width: 100%; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
  </div>
  <div style="flex: 1; max-width: 500px;">
    <h3 style="color: #334D94; margin-bottom: 15px;">File Blotter</h3>
    <p style="font-size: 1em; color: #444; line-height: 1.6;">
      The File Blotter in the Barangay Information Management System (BIMS) is used to record and manage incidents, complaints, and disputes reported within the community. Through the BIMS platform, barangay officials can log detailed information about each case, including the type of incident, involved parties, and actions taken. This digital system ensures that all reports are securely stored, easily searchable, and properly tracked. It helps barangay officials respond promptly, monitor case progress, and maintain transparency. The File Blotter streamlines the documentation process, reduces manual errors, and supports fair and organized handling of community concerns.
    </p>
  </div>
</section>


<section style="padding: 60px 20px; background-color: #e9ecf5;">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="fw-bold">Report Summary</h2>
      <p class="text-muted">Visual insights and data trends based on barangay operations and activities.</p>
    </div>

    <div class="row justify-content-center">
      <div class="col-md-8 text-center">
        <img src="image/graph.png" alt="Barangay Report Graph" class="img-fluid rounded shadow">
      </div>
    </div>
  </div>
</section>

<footer class="footer text-white pt-5 pb-4" style="background-color: #132440;">
  <div class="container text-md-left">
    <div class="row text-md-left">

      <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
        <h5 class="text-uppercase mb-4 font-weight-bold">Barangay System</h5>
        <p>Empowering communities with efficient and transparent digital services for better governance and connection.</p>
      </div>

      <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
        <h5 class="text-uppercase mb-4 font-weight-bold">Quick Links</h5>
        <p><a href="homepage.php" class="text-white" style="text-decoration: none;">Home</a></p>
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
        <p>© 2025 Barangay 468 Information Management System. All Rights Reserved.</p>
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
