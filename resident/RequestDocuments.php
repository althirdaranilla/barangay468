<?php
session_start();

// Check login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Request Documents | Barangay Dashboard</title>
  <link rel="stylesheet" href="../css/resident.css">
</head>
<body>

  <!-- Sidebar -->
  <aside class="sidebar" id="sidebar">
        <div class="logo">
            <img src="../images/logo.png" alt="Logo" class="logo-img">
            <div class="logo-text">Barangay<br>Management</div>
        </div>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="Dashboard.php" class="nav-link <?php echo $current_page === 'dashboard' ? 'active' : ''; ?>">
                    <div class="nav-link-content">
                        <div class="nav-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                        </div>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>

            <li class="nav-item">
                <a href="RequestDocuments.php" class="nav-link">
                    <div class="nav-link-content">
                        <div class="nav-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                        </div>
                        <span>Request Documents</span>
                    </div>
                </a>
            </li>

            <li class="nav-item">
                <a href="ManageRequests.php" class="nav-link">
                    <div class="nav-link-content">
                        <div class="nav-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                        </div>
                        <span>Manage Documents</span>
                    </div>
                </a>
            </li>
        </ul>

        <div class="logout-section">
            <a href="../logout.php" class="nav-link" onclick="showLogoutModal(event)">
                <div class="nav-link-content">
                    <div class="nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </div>
                    <span>Log out</span>
                </div>
            </a>
        </div>
    </aside>

  <!-- Main Content -->
  <main class="main-content">
      <header class="header">
          <h1 class="welcome-text">Welcome, <?php echo htmlspecialchars($user_name); ?></h1>
          <div class="header-right">
              <div class="admin-avatar"><?php echo strtoupper(substr($user_name, 0, 1)); ?></div>
          </div>
      </header>

      <section class="dashboard-section">
          <!-- Tabs -->
          <div class="tab-container">
              <button class="tab-button active" onclick="openTab('clearance')">Clearance Form</button>
              <button class="tab-button" onclick="openTab('permit')">Permit Form</button>
              <button class="tab-button" onclick="openTab('certificate')">Certificate Form</button>
          </div>

          <!-- Clearance Form -->
          <div id="clearance" class="tab-content active">
              <form action="submit-clearance.php" method="POST" class="clearance-form">
                  <div class="form-grid">
                      <div class="form-group full-name">
                          <label>Fullname:</label>
                          <div class="name-fields">
                              <input type="text" name="first_name" placeholder="Enter first name" required>
                              <input type="text" name="middle_name" placeholder="Enter middle name">
                              <input type="text" name="last_name" placeholder="Enter last name" required>
                          </div>
                      </div>

                      <div class="form-group">
                          <label>Email:</label>
                          <input type="email" name="email" placeholder="sample@gmail.com" required>
                      </div>

                      <div class="form-group">
                          <label>Cellphone no:</label>
                          <input type="text" name="cellphone" placeholder="Enter phone number" required>
                      </div>

                      <div class="form-group">
                          <label>Address:</label>
                          <input type="text" name="address" placeholder="Enter address" required>
                      </div>

                      <div class="form-group">
                          <label>Household Number:</label>
                          <input type="text" name="household_number" placeholder="Enter household number" required>
                      </div>

                      <div class="form-group">
                          <label>Purpose:</label>
                          <input type="text" name="purpose" placeholder="Enter purpose of clearance" required>
                      </div>
                  </div>

                  <div class="form-submit">
                      <button type="submit" class="submit-btn">Submit</button>
                  </div>
              </form>
          </div>

          <!-- Permit Form -->
          <div id="permit" class="tab-content">
              <form action="submit-permit.php" method="POST" class="permit-form">
                  <div class="form-grid">
                      <div class="form-group full-name">
                          <label>Fullname:</label>
                          <div class="name-fields">
                              <input type="text" name="first_name" placeholder="Enter first name" required>
                              <input type="text" name="middle_name" placeholder="Enter middle name">
                              <input type="text" name="last_name" placeholder="Enter last name" required>
                          </div>
                      </div>

                      <div class="form-group">
                          <label>Email:</label>
                          <input type="email" name="email" placeholder="sample@gmail.com" required>
                      </div>

                      <div class="form-group">
                          <label>Cellphone no:</label>
                          <input type="text" name="cellphone" placeholder="Enter phone number" required>
                      </div>

                      <div class="form-group">
                          <label>Address:</label>
                          <input type="text" name="address" placeholder="Enter address" required>
                      </div>

                      <div class="form-group">
                          <label>Household Number:</label>
                          <input type="text" name="household_number" placeholder="Enter household number" required>
                      </div>

                      <div class="form-group">
                          <label>Purpose:</label>
                          <input type="text" name="purpose" placeholder="Enter purpose of clearance" required>
                      </div>

                      <div class="form-group">
                          <label>Type of Permit:</label>
                          <select name="permit_type" required>
                              <option value="" disabled selected>Select an option</option>
                              <option value="Business Permit">Business Permit</option>
                              <option value="Building Permit">Building Permit</option>
                              <option value="Event Permit">Event Permit</option>
                          </select>
                      </div>
                  </div>

                  <div class="form-submit">
                      <button type="submit" class="submit-btn">Submit</button>
                  </div>
              </form>
          </div>

          <!-- Certificate Form -->
          <div id="certificate" class="tab-content">
              <form action="submit-certificate.php" method="POST" class="certificate-form">
                  <div class="form-grid">
                      <div class="form-group full-name">
                          <label>Fullname:</label>
                          <div class="name-fields">
                              <input type="text" name="first_name" placeholder="Enter first name" required>
                              <input type="text" name="middle_name" placeholder="Enter middle name">
                              <input type="text" name="last_name" placeholder="Enter last name" required>
                          </div>
                      </div>

                      <div class="form-group">
                          <label>Email:</label>
                          <input type="email" name="email" placeholder="sample@gmail.com" required>
                      </div>

                      <div class="form-group">
                          <label>Cellphone no:</label>
                          <input type="text" name="cellphone" placeholder="Enter phone number" required>
                      </div>

                      <div class="form-group">
                          <label>Address:</label>
                          <input type="text" name="address" placeholder="Enter address" required>
                      </div>

                      <div class="form-group">
                          <label>Household Number:</label>
                          <input type="text" name="household_number" placeholder="Enter Household number" required>
                      </div>

                      <div class="form-group">
                          <label>Purpose:</label>
                          <input type="text" name="purpose" placeholder="Enter purpose of clearance" required>
                      </div>

                      <div class="form-group">
                          <label>Type of Certificate:</label>
                          <select name="certificate_type" required>
                              <option value="" disabled selected>Select an option</option>
                              <option value="Residency Certificate">Residency Certificate</option>
                              <option value="Indigency Certificate">Indigency Certificate</option>
                              <option value="Employment Certificate">Employment Certificate</option>
                          </select>
                      </div>
                  </div>

                  <div class="form-submit">
                      <button type="submit" class="submit-btn">Submit</button>
                  </div>
              </form>
          </div>

      </section>
  </main>
  
  <!-- Logout Confirmation Modal -->
<div id="logoutModal" class="logout-modal">
    <div class="logout-modal-content">
        <h3>Confirm Logout</h3>
        <p>Are you sure you want to log out?</p>
        <div class="logout-modal-buttons">
            <button class="logout-btn confirm" onclick="confirmLogout()">Yes, Logout</button>
            <button class="logout-btn cancel" onclick="closeLogoutModal()">Cancel</button>
        </div>
    </div>
</div>

  <script src="../js/resident.js"></script>
  <script>
    function openTab(tabName) {
        const tabs = document.querySelectorAll('.tab-content');
        const buttons = document.querySelectorAll('.tab-button');
        tabs.forEach(tab => tab.classList.remove('active'));
        buttons.forEach(btn => btn.classList.remove('active'));
        document.getElementById(tabName).classList.add('active');
        event.target.classList.add('active');
    }
  </script>

</body>
</html>
