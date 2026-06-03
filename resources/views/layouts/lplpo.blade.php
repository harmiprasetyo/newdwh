<!DOCTYPE html>
<html>
<head>
    <title>LPLPO System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">
    <style>
        body {
            display: flex;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #0d6efd;
            color: white;
            padding: 20px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,0.2);
        }

        .content {
            flex: 1;
            padding: 20px;
            background: #f8f9fa;
        }

        .table-container {
    overflow-x: auto;
}

#table_lplpo {
    width: 100%;
    white-space: nowrap;
    font-size: 13px;
}

#table_lplpo thead th {
    background: #f8f9fa;
    text-align: center;
    vertical-align: middle;
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
}

#table_lplpo tbody td {
    vertical-align: middle;
}

/* Sticky header */
#table_lplpo thead th {
    position: sticky;
    top: 0;
    z-index: 2;
}

/* Zebra */
#table_lplpo tbody tr:nth-child(even) {
    background: #fafafa;
}
    </style>
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
</head>
<body>

<div class="sidebar">
    <h4 class="mb-4">LPLPO</h4>
     <a href="/homepage">
       Beranda
    </a>

    <a href="/lplpo/dashboard">📊 Dashboard</a>
    @if(session('group') == 3)
    <a href="/lplpo/upload">📤 Upload LPLPO</a>
@endif

    <a href="/lplpo/dataview">📋 Data LPLPO</a>
    <a href="/lplpo-final">📋 LPLPO Final</a>

     <a href="/logout">
       Logout
    </a>
</div>



<div class="content">
    @yield('content')
</div>

</body>
</html>
