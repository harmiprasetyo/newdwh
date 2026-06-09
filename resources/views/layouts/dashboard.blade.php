<!DOCTYPE html>
<html>
<head>
    <title>Dashboard LPLPO</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        body { overflow-x: hidden; }
        .sidebar {
            height: 100vh;
            background: #1e293b;
            color: white;
            padding: 20px;
        }
        .sidebar a {
            color: #cbd5e1;
            display: block;
            padding: 10px;
            text-decoration: none;
        }
        .sidebar a:hover, .active {
            background: #334155;
            color: white;
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-2 sidebar">
            <h5>📊 LPLPO</h5>
            <hr>

            <a href="/homepage">🏠 Beranda</a>
            <a href="/lplpo" class="active">📈 Dashboard</a>
            <a href="/logout">🚪 Logout</a>
        </div>

        <!-- CONTENT -->
        <div class="col-md-10 p-4">
            @yield('content')
        </div>

    </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>
