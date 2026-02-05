                <!-- Footer -->
                <footer class="mt-5 pt-3 border-top">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted">
                                &copy; <?php echo date('Y'); ?> MCC Lost & Found System. All rights reserved.
                            </p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p class="text-muted">
                                <i class="bi bi-clock"></i> Philippine Time: <?php echo date('F j, Y, g:i a'); ?>
                            </p>
                        </div>
                    </div>
                </footer>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- DataTables -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Initialize DataTables
        $(document).ready(function() {
            $('.data-table').DataTable({
                "pageLength": 25,
                "order": [[0, 'desc']],
                "responsive": true
            });
            
            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
            
            // Confirm delete actions
            $('.confirm-delete').on('click', function() {
                return confirm('Are you sure you want to delete this item? This action cannot be undone.');
            });
            
            // Toggle sidebar on mobile
            $('#sidebarToggle').on('click', function() {
                $('#sidebar').toggleClass('show');
            });
        });
        
        // Format date for display
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
        
        // Get status badge HTML
        function getStatusBadge(status) {
            const statusClass = status.toLowerCase();
            return `<span class="status-badge status-${statusClass}">${status}</span>`;
        }
        
        // Preview image before upload
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px;">`;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>