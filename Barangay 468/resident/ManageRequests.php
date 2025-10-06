<?php
session_start();

// Check login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once('includes/db_connect.php');

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'User';

// Helper function for colored status
function getStatusClass($status) {
    $status = strtolower(trim($status));
    return match($status) {
        'approved' => 'status-approved',
        'pending' => 'status-pending',
        'rejected' => 'status-rejected',
        default => 'status-default'
    };
}

// Fetch data
$stmt1 = $pdo->prepare("SELECT * FROM clearance_requests WHERE user_id = ?");
$stmt1->execute([$user_id]);
$clearance_requests = $stmt1->fetchAll(PDO::FETCH_ASSOC);

$stmt2 = $pdo->prepare("SELECT * FROM permit_requests WHERE user_id = ?");
$stmt2->execute([$user_id]);
$permit_requests = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$stmt3 = $pdo->prepare("SELECT * FROM certificate_requests WHERE user_id = ?");
$stmt3->execute([$user_id]);
$certificate_requests = $stmt3->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Requests | Barangay Dashboard</title>
  <link rel="stylesheet" href="../css/resident.css">
  <style>
    .status-approved { background:#2ecc71; color:#fff; padding:4px 10px; border-radius:8px; font-weight:600; }
    .status-pending { background:#f1c40f; color:#000; padding:4px 10px; border-radius:8px; font-weight:600; }
    .status-rejected { background:#e74c3c; color:#fff; padding:4px 10px; border-radius:8px; font-weight:600; }
    .status-default { background:#bdc3c7; color:#555; padding:4px 10px; border-radius:8px; font-weight:600; }
    .action-btn { padding:6px 12px; border-radius:6px; font-size:14px; cursor:pointer; border:none; }
    .view-btn { background:#3498db; color:#fff; }
    .delete-btn { background:#e74c3c; color:#fff; }
    .view-btn:hover { background:#2980b9; }
    .delete-btn:hover { background:#c0392b; }
    .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:1000; }
    .modal-content { background:#fff; padding:20px; border-radius:10px; max-width:500px; width:90%; max-height:80vh; overflow-y:auto; }
    .modal-content table { width:100%; border-collapse:collapse; }
    .modal-content td { padding:6px 8px; border-bottom:1px solid #ddd; }
    .modal-close { background:#ccc; padding:6px 12px; border:none; border-radius:5px; cursor:pointer; margin-top:10px; }
  </style>
</head>
<body>

<!-- ✅ Sidebar -->
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

<!-- ✅ Main Content -->
<main class="main-content">
  <header class="header">
    <h1 class="welcome-text">Your Requests, <?php echo htmlspecialchars($user_name); ?></h1>
    <div class="header-right"><div class="admin-avatar"><?php echo strtoupper(substr($user_name, 0, 1)); ?></div></div>
  </header>

  <section class="dashboard-section">
    <div class="tab-container">
      <button class="tab-button active" onclick="openTab('clearance')">Clearance Requests</button>
      <button class="tab-button" onclick="openTab('permit')">Permit Requests</button>
      <button class="tab-button" onclick="openTab('certificate')">Certificate Requests</button>
    </div>

    <!-- ✅ Clearance -->
    <div id="clearance" class="tab-content active">
      <h2>Barangay Clearance Requests</h2>
      <?php if ($clearance_requests): ?>
      <table class="request-table">
        <thead>
          <tr><th>Name</th><th>Purpose</th><th>Status</th><th>Date</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php foreach ($clearance_requests as $req): ?>
          <tr>
            <td><?= htmlspecialchars($req['first_name'].' '.$req['middle_name'].' '.$req['last_name']) ?></td>
            <td><?= htmlspecialchars($req['purpose']) ?></td>
            <td><span class="<?= getStatusClass($req['status']); ?>"><?= htmlspecialchars($req['status']); ?></span></td>
            <td><?= htmlspecialchars($req['date_requested']); ?></td>
            <td>
              <button class="action-btn view-btn" onclick='viewRequest(<?= json_encode($req) ?>)'>View</button>
              <button class="action-btn delete-btn" onclick="deleteRequest('clearance_requests', <?= $req['id'] ?>)">Delete</button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?><p class="no-data">No clearance requests found.</p><?php endif; ?>
    </div>

    <!-- ✅ Permit -->
    <div id="permit" class="tab-content">
      <h2>Barangay Permit Requests</h2>
      <?php if ($permit_requests): ?>
      <table class="request-table">
        <thead>
          <tr><th>Name</th><th>Type</th><th>Purpose</th><th>Status</th><th>Date</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php foreach ($permit_requests as $req): ?>
          <tr>
            <td><?= htmlspecialchars($req['first_name'].' '.$req['middle_name'].' '.$req['last_name']) ?></td>
            <td><?= htmlspecialchars($req['permit_type']) ?></td>
            <td><?= htmlspecialchars($req['purpose']) ?></td>
            <td><span class="<?= getStatusClass($req['status']); ?>"><?= htmlspecialchars($req['status']); ?></span></td>
            <td><?= htmlspecialchars($req['date_requested']); ?></td>
            <td>
              <button class="action-btn view-btn" onclick='viewRequest(<?= json_encode($req) ?>)'>View</button>
              <button class="action-btn delete-btn" onclick="deleteRequest('permit_requests', <?= $req['id'] ?>)">Delete</button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?><p class="no-data">No permit requests found.</p><?php endif; ?>
    </div>

    <!-- ✅ Certificate -->
    <div id="certificate" class="tab-content">
      <h2>Barangay Certificate Requests</h2>
      <?php if ($certificate_requests): ?>
      <table class="request-table">
        <thead>
          <tr><th>Name</th><th>Type</th><th>Purpose</th><th>Status</th><th>Date</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php foreach ($certificate_requests as $req): ?>
          <tr>
            <td><?= htmlspecialchars($req['first_name'].' '.$req['middle_name'].' '.$req['last_name']) ?></td>
            <td><?= htmlspecialchars($req['certificate_type']) ?></td>
            <td><?= htmlspecialchars($req['purpose']) ?></td>
            <td><span class="<?= getStatusClass($req['status']); ?>"><?= htmlspecialchars($req['status']); ?></span></td>
            <td><?= htmlspecialchars($req['date_requested']); ?></td>
            <td>
              <button class="action-btn view-btn" onclick='viewRequest(<?= json_encode($req) ?>)'>View</button>
              <button class="action-btn delete-btn" onclick="deleteRequest('certificate_requests', <?= $req['id'] ?>)">Delete</button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?><p class="no-data">No certificate requests found.</p><?php endif; ?>
    </div>
  </section>
</main>

<!-- ✅ View Modal -->
<div id="viewModal" class="modal">
  <div class="modal-content">
    <h3>Request Details</h3>
    <div id="modalDetails"></div>
    <button class="modal-close" onclick="closeModal()">Close</button>
  </div>
</div>

<script src="../js/resident.js"></script>
<script>
function openTab(tabName) {
  document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
  document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
  document.getElementById(tabName).classList.add('active');
  event.target.classList.add('active');
}

// ✅ View details
function viewRequest(data) {
  const modal = document.getElementById('viewModal');
  const details = document.getElementById('modalDetails');
  let html = '<table>';
  for (const [key, value] of Object.entries(data)) {
    if (key !== 'user_id') {
      html += `<tr><td><strong>${key.replaceAll('_', ' ')}</strong></td><td>${value ?? ''}</td></tr>`;
    }
  }
  html += '</table>';
  details.innerHTML = html;
  modal.style.display = 'flex';
}
function closeModal() { document.getElementById('viewModal').style.display = 'none'; }

// ✅ Delete request via AJAX
function deleteRequest(table, id) {
  if (!confirm('Are you sure you want to delete this request?')) return;
  fetch('delete_request.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `table=${table}&id=${id}`
  })
  .then(res => res.text())
  .then(msg => {
    alert(msg);
    location.reload();
  })
  .catch(() => alert('Error deleting request.'));
}
</script>

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

</body>
</html>
