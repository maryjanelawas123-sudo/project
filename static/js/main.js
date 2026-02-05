/**
 * MCC Lost & Found System - Main JavaScript
 */

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize DataTables if present
    initDataTables();
    
    // Initialize form validations
    initFormValidations();
    
    // Initialize tooltips
    initTooltips();
    
    // Initialize image previews
    initImagePreviews();
    
    // Auto-dismiss alerts after 5 seconds
    autoDismissAlerts();
    
    // Initialize confirm delete actions
    initConfirmDelete();
    
    // Initialize AJAX handlers
    initAjaxHandlers();
    
    // Initialize mobile menu toggle
    initMobileMenu();
});

/**
 * Initialize DataTables
 */
function initDataTables() {
    if (typeof $.fn.DataTable !== 'undefined') {
        $('.data-table').DataTable({
            "pageLength": 25,
            "order": [[0, 'desc']],
            "responsive": true,
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "Showing 0 to 0 of 0 entries",
                "infoFiltered": "(filtered from _MAX_ total entries)",
                "zeroRecords": "No matching records found",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                }
            }
        });
    }
}

/**
 * Initialize form validations
 */
function initFormValidations() {
    // Password confirmation validation
    const passwordForms = document.querySelectorAll('form[action*="change-password"], form[action*="signup"]');
    passwordForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const password = this.querySelector('input[name="new_password"], input[name="password"]');
            const confirmPassword = this.querySelector('input[name="confirm_password"]');
            
            if (password && confirmPassword) {
                if (password.value !== confirmPassword.value) {
                    e.preventDefault();
                    showAlert('Passwords do not match!', 'danger');
                    return false;
                }
                
                if (password.value.length < 6) {
                    e.preventDefault();
                    showAlert('Password must be at least 6 characters!', 'danger');
                    return false;
                }
            }
        });
    });
    
    // Required field validation
    const requiredForms = document.querySelectorAll('form[data-validate="true"]');
    requiredForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            let firstInvalidField = null;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    if (!firstInvalidField) firstInvalidField = field;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showAlert('Please fill in all required fields!', 'danger');
                if (firstInvalidField) firstInvalidField.focus();
                return false;
            }
        });
    });
}

/**
 * Initialize tooltips
 */
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Initialize image previews
 */
function initImagePreviews() {
    document.querySelectorAll('input[type="file"][accept*="image"]').forEach(input => {
        input.addEventListener('change', function(e) {
            const file = this.files[0];
            const previewId = this.dataset.preview || 'imagePreview';
            const preview = document.getElementById(previewId);
            
            if (file && preview) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px;">`;
                }
                reader.readAsDataURL(file);
            }
        });
    });
}

/**
 * Auto-dismiss alerts after 5 seconds
 */
function autoDismissAlerts() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        if (alert.classList.contains('alert-dismissible')) {
            setTimeout(() => {
                const closeBtn = alert.querySelector('.btn-close');
                if (closeBtn) closeBtn.click();
            }, 5000);
        }
    });
}

/**
 * Initialize confirm delete actions
 */
function initConfirmDelete() {
    document.querySelectorAll('.confirm-delete').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
                return false;
            }
        });
    });
}

/**
 * Initialize AJAX handlers
 */
function initAjaxHandlers() {
    // AJAX form submissions
    document.querySelectorAll('form[data-ajax="true"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
            submitBtn.disabled = true;
            
            fetch(this.action, {
                method: this.method,
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1500);
                    }
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => {
                showAlert('An error occurred. Please try again.', 'danger');
                console.error('Error:', error);
            })
            .finally(() => {
                // Reset button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    });
}

/**
 * Initialize mobile menu
 */
function initMobileMenu() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    }
}

/**
 * Show alert message
 */
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Find alert container or create one
    let alertContainer = document.querySelector('.alert-container');
    if (!alertContainer) {
        alertContainer = document.createElement('div');
        alertContainer.className = 'alert-container position-fixed top-0 end-0 p-3';
        alertContainer.style.zIndex = '1060';
        document.body.appendChild(alertContainer);
    }
    
    alertContainer.appendChild(alertDiv);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

/**
 * Format date for display
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

/**
 * Get status badge HTML
 */
function getStatusBadge(status) {
    const statusClass = status.toLowerCase();
    return `<span class="status-badge status-${statusClass}">${status}</span>`;
}

/**
 * Load more items via AJAX
 */
function loadMoreItems(containerId, url, page = 1) {
    const container = document.getElementById(containerId);
    const loadMoreBtn = container.querySelector('.load-more-btn');
    
    if (loadMoreBtn) {
        loadMoreBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Loading...';
        loadMoreBtn.disabled = true;
    }
    
    fetch(`${url}?page=${page}`)
        .then(response => response.json())
        .then(data => {
            if (data.items && data.items.length > 0) {
                data.items.forEach(item => {
                    const itemHtml = createItemHtml(item);
                    container.insertAdjacentHTML('beforeend', itemHtml);
                });
                
                if (loadMoreBtn) {
                    if (data.hasMore) {
                        loadMoreBtn.dataset.page = page + 1;
                        loadMoreBtn.innerHTML = 'Load More';
                        loadMoreBtn.disabled = false;
                    } else {
                        loadMoreBtn.remove();
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error loading more items:', error);
            if (loadMoreBtn) {
                loadMoreBtn.innerHTML = 'Load More';
                loadMoreBtn.disabled = false;
            }
        });
}

/**
 * Create item HTML for AJAX loading
 */
function createItemHtml(item) {
    return `
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">${item.name}</h5>
                    <p class="card-text">${item.description ? item.description.substring(0, 100) + '...' : 'No description'}</p>
                    <span class="status-badge status-${item.status.toLowerCase()}">${item.status}</span>
                </div>
            </div>
        </div>
    `;
}

/**
 * Export data to CSV
 */
function exportToCSV(data, filename) {
    const csvContent = "data:text/csv;charset=utf-8," + data.map(row => row.join(",")).join("\n");
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

/**
 * Print specific element
 */
function printElement(elementId) {
    const printContent = document.getElementById(elementId);
    const originalContent = document.body.innerHTML;
    
    document.body.innerHTML = printContent.innerHTML;
    window.print();
    document.body.innerHTML = originalContent;
    window.location.reload();
}

/**
 * Copy text to clipboard
 */
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showAlert('Copied to clipboard!', 'success');
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}

/**
 * Toggle password visibility
 */
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.setAttribute('type', type);
}

/**
 * Search items dynamically
 */
function searchItems(query, containerId) {
    const container = document.getElementById(containerId);
    const items = container.querySelectorAll('.item-card');
    
    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(query.toLowerCase())) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}

/**
 * Initialize countdown timer
 */
function initCountdownTimer(elementId, endDate) {
    const countdownElement = document.getElementById(elementId);
    
    function updateCountdown() {
        const now = new Date().getTime();
        const distance = endDate - now;
        
        if (distance < 0) {
            countdownElement.innerHTML = "EXPIRED";
            return;
        }
        
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        countdownElement.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
    }
    
    updateCountdown();
    setInterval(updateCountdown, 1000);
}

/**
 * Validate email format
 */
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Validate phone number format
 */
function validatePhone(phone) {
    const re = /^[\+]?[0-9\s\-\(\)]{7,15}$/;
    return re.test(phone);
}

/**
 * Debounce function for search inputs
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Throttle function for scroll events
 */
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}