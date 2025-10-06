// dashboard.js

// Toggle sidebar for mobile
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('active');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (window.innerWidth <= 768) {
        if (!sidebar.contains(event.target) && !mobileMenu.contains(event.target)) {
            sidebar.classList.remove('active');
        }
    }
});

// Toggle submenu
function toggleSubmenu(event, submenuId) {
    event.preventDefault();
    const submenu = document.getElementById(submenuId);
    const navLink = event.currentTarget;
    
    // Toggle expanded class
    navLink.classList.toggle('expanded');
    submenu.classList.toggle('expanded');
}

// Show logout modal
function showLogoutModal(event) {
    event.preventDefault();
    const modal = document.getElementById('logoutModal');
    modal.style.display = 'block';
}

// Close logout modal
function closeLogoutModal() {
    const modal = document.getElementById('logoutModal');
    modal.style.display = 'none';
}

// Confirm logout
function confirmLogout() {
    // Redirect to logout script
    window.location.href = '../logout.php';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('logoutModal');
    if (event.target == modal) {
        closeLogoutModal();
    }
}

// View resident details
function viewResident(id) {
    window.location.href = `view-resident.php?id=${id}`;
}

// Edit resident
function editResident(id) {
    window.location.href = `edit-resident.php?id=${id}`;
}

// Delete resident
function deleteResident(id) {
    if (confirm('Are you sure you want to delete this resident?')) {
        // Send AJAX request to delete
        fetch('delete-resident.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Resident deleted successfully');
                location.reload();
            } else {
                alert('Error deleting resident: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the resident');
        });
    }
}

// Search functionality
const searchInput = document.querySelector('.search-input');
if (searchInput) {
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const table = document.querySelector('.residents-table tbody');
        
        if (table) {
            const rows = table.getElementsByTagName('tr');
            
            for (let row of rows) {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }
    });
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard loaded successfully');
    
    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    // 🔹 Tab Switching
    const tabs = document.querySelectorAll('.tab-btn');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(btn => {
        btn.addEventListener('click', () => {
            tabs.forEach(b => b.classList.remove('active'));
            contents.forEach(c => c.classList.remove('active'));

            btn.classList.add('active');
            document.getElementById(btn.dataset.tab).classList.add('active');
        });
    });
});

  document.addEventListener('DOMContentLoaded', () => {
      const modal = document.getElementById('viewModal');
      const modalBody = modal.querySelector('.modal-body');
      const closeModal = modal.querySelector('.close');

      // ✅ View button click
      document.querySelectorAll('.action-btn.view').forEach(btn => {
          btn.addEventListener('click', e => {
              const row = e.target.closest('tr');
              const details = JSON.parse(row.dataset.details);

              modalBody.innerHTML = `
                  <p><strong>Full Name:</strong> ${details.first_name} ${details.middle_name || ''} ${details.last_name}</p>
                  <p><strong>Email:</strong> ${details.email}</p>
                  <p><strong>Cellphone:</strong> ${details.cellphone}</p>
                  <p><strong>Address:</strong> ${details.address}</p>
                  <p><strong>Household #:</strong> ${details.household_number}</p>
                  <p><strong>Purpose:</strong> ${details.purpose}</p>
                  <p><strong>Status:</strong> ${details.status}</p>
                  <p><strong>Pickup Schedule:</strong> ${details.pickup_schedule ?? 'N/A'}</p>
                  <p><strong>Feedback:</strong> ${details.feedback ?? 'N/A'}</p>
                  <p><strong>Date Requested:</strong> ${details.date_requested}</p>
              `;
              modal.style.display = 'block';
          });
      });

      // ✅ Close modal
      closeModal.addEventListener('click', () => modal.style.display = 'none');
      window.addEventListener('click', e => {
          if (e.target === modal) modal.style.display = 'none';
      });

      // ✅ Delete button click
      document.querySelectorAll('.action-btn.delete').forEach(btn => {
          btn.addEventListener('click', async e => {
              const row = e.target.closest('tr');
              const details = JSON.parse(row.dataset.details);
              if (!confirm(`Are you sure you want to delete request #${details.id}?`)) return;

              const formData = new FormData();
              formData.append('delete_id', details.id);

              const res = await fetch('ManageRequests.php', {
                  method: 'POST',
                  body: formData
              });
              const data = await res.json();

              if (data.success) {
                  alert('Request deleted successfully!');
                  row.remove();
              } else {
                  alert('Failed to delete: ' + (data.message || 'Unknown error'));
              }
          });
      });
  });