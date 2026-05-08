<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



    <style>
        body { background: #f8f9fa; }


        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: #1e1e2d;
            color: white;
        }

        .sidebar a {
            color: #ccc;
            padding: 12px;
            display: block;
            text-decoration: none;
        }

        .sidebar a.active {
            background: #0d6efd;
            color: #fff !important;
            border-radius: 6px;
        }

        .sidebar a:hover {
            background: #2c2c3e;
            color: white;
        }

        .submenu {
            display: none;
            padding-left: 15px;
        }

        .content {
            margin-left: 250px;
        }

        .topbar {
            height: 60px;
            background: white;
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }

        .card-dashboard {
            border-radius: 12px;
            padding: 20px;
            color: white;
        }
    </style>
</head>
