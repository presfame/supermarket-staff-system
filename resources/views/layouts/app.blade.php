<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manimo Supermarket - @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
        /* Toast Styles */
        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; }
        .custom-toast { padding: 16px 24px; border-radius: 10px; color: white; display: flex; align-items: center; gap: 12px; margin-bottom: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); animation: slideIn 0.3s ease; min-width: 300px; }
        .custom-toast.success { background: var(--success); }
        .custom-toast.error { background: var(--danger); }
        .custom-toast.info { background: var(--primary); }
        .custom-toast .toast-close { margin-left: auto; cursor: pointer; opacity: 0.8; }
        .custom-toast .toast-close:hover { opacity: 1; }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
        
        /* DataTables Custom Styles */
        .dataTables_wrapper .dataTables_filter input { border-radius: 8px; border: 1px solid var(--border-color); padding: 8px 12px; }
        .dataTables_wrapper .dataTables_length select { border-radius: 8px; border: 1px solid var(--border-color); padding: 6px 12px; }
        .dataTables_wrapper .dataTables_info { color: var(--text-muted); font-size: 13px; }
        .dataTables_wrapper .dataTables_paginate .paginate_button { border-radius: 6px !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: var(--primary) !important; border-color: var(--primary) !important; color: white !important; }
        .dataTables_wrapper .dataTables_filter { margin-bottom: 16px; }
        .dataTables_wrapper .dataTables_length { margin-bottom: 16px; }
        table.dataTable thead th { background: #f8fafc; font-weight: 600; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; color: #64748b; }
        
        /* Date icon styling */
        .date-display { display: inline-flex; align-items: center; gap: 6px; }
        .date-display i { color: var(--primary); font-size: 12px; }
    </style>
</head>
<body>
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <div class="wrapper">
        @include('layouts.sidebar')
        <div class="main-content">
            @include('layouts.navbar')
            <div class="content-wrapper">
                <div class="container-fluid px-4 py-4">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        // Toast function
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `custom-toast ${type}`;
            toast.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
                <span>${message}</span>
                <span class="toast-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></span>
            `;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        // Show session messages as toasts
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif

        // Initialize DataTables on all tables with class 'datatable'
        $(document).ready(function() {
            $('.datatable').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                language: {
                    search: '<i class="fas fa-search"></i>',
                    searchPlaceholder: 'Search...',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>'
                    }
                },
                dom: '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>'
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
