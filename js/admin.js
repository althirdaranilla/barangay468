// Dropdown functionality
function toggleDropdown(index, event) {
    // Only toggle dropdown, don't prevent default navigation
    const navLink = document.querySelector(`#sidebar .nav-item:nth-child(${index + 1}) .nav-link`);
    const submenu = document.getElementById(`submenu-${index}`);
    
    if (navLink && submenu) {
        navLink.classList.toggle('expanded');
        submenu.classList.toggle('expanded');
        
        // Close other open dropdowns
        document.querySelectorAll('.submenu').forEach((menu) => {
            if (menu.id !== `submenu-${index}` && menu.classList.contains('expanded')) {
                menu.classList.remove('expanded');
                const parentLink = menu.previousElementSibling;
                if (parentLink && parentLink.classList.contains('nav-link')) {
                    parentLink.classList.remove('expanded');
                }
            }
        });
    }
}

// Sidebar toggle for mobile
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('active');
}

// Logout functionality
function showLogoutModal() {
    document.getElementById('logoutModal').style.display = 'block';
}

function closeLogoutModal() {
    document.getElementById('logoutModal').style.display = 'none';
}

function confirmLogout() {
    window.location.href = '../logout.php';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('logoutModal');
    if (event.target == modal) {
        closeLogoutModal();
    }
};

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (window.innerWidth <= 768 && 
        !sidebar.contains(event.target) && 
        !mobileMenu.contains(event.target) &&
        !event.target.classList.contains('mobile-menu')) {
        sidebar.classList.remove('active');
    }
});

// Search functionality
document.getElementById('searchInput')?.addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    filterResidents(searchTerm);
});

// Filter residents based on search term
function filterResidents(searchTerm) {
    const table = document.getElementById('residentsTable');
    if (!table) return;
    
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;
        
        for (let j = 0; j < cells.length; j++) {
            const cellText = cells[j].textContent || cells[j].innerText;
            if (cellText.toLowerCase().includes(searchTerm)) {
                found = true;
                break;
            }
        }
        
        row.style.display = found ? '' : 'none';
    }
}

// Initialize dropdowns based on PHP data
document.addEventListener('DOMContentLoaded', function() {
    // Auto-expand items that are marked as expanded in PHP
    const expandedItems = document.querySelectorAll('.nav-link.expanded');
    expandedItems.forEach(item => {
        const submenu = item.nextElementSibling;
        if (submenu && submenu.classList.contains('submenu')) {
            submenu.classList.add('expanded');
        }
    });

    // Add click event listeners to parent dropdown links to prevent navigation
    document.querySelectorAll('.nav-link.expandable').forEach(link => {
        link.addEventListener('click', function(event) {
            // Only prevent default if clicking the parent link (not submenu items)
            if (!event.target.closest('.submenu-item')) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    });

    // Ensure submenu items can navigate normally
    document.querySelectorAll('.submenu-item').forEach(submenuItem => {
        submenuItem.addEventListener('click', function(event) {
            // Allow normal navigation for submenu items
            // No need to prevent default or stop propagation
        });
    });
});

// Update entries per page
function updateEntries(entries) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('entries', entries);
    urlParams.set('page', '1'); // Reset to first page when changing entries
    window.location.href = `?${urlParams.toString()}`;
}

// Format numbers for display
function formatNumber(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Initialize entries select with current value
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const currentEntries = urlParams.get('entries') || '5';
    const entriesSelect = document.querySelector('.entries-select');
    if (entriesSelect) {
        entriesSelect.value = currentEntries;
    }
});