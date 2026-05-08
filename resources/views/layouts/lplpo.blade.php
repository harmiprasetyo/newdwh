<!DOCTYPE html>
<html>
<head>
    <title>LPLPO System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>
<body>

<div class="sidebar">
    <h4 class="mb-4">LPLPO</h4>

    <a href="/lplpo/dashboard">📊 Dashboard</a>
    <a href="/lplpo/upload">📤 Upload LPLPO</a>
    <a href="/lplpo/dataview">📋 Data LPLPO</a>
</div>

<div class="content">
    @yield('content')
</div>

</body>
</html>
