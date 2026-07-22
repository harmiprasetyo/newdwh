<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>@yield('title')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('bs538/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
    <link  href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/5.3.5/apexcharts-legend.min.css" integrity="sha512-c+q4lJ9pAoiVNqS+1EXJ6yo6RnbGN3stU46/3OuQ8S468g6iMdj62TQU8H9UZ3I2xSy7VrY6jRDtFVtPeKAX8w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/5.3.5/apexcharts.min.css" integrity="sha512-IqtQ7LKr3He47p7HjxynmqZfN07VljNkdGyGDdDJ//f1r6bT0IEKQf2CCtSgun/pvbFlNnPDMRrMSQhmSxmSSg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">

    <style>

        body{

            overflow:hidden;

            background:#f4f6f9;

        }

        .sidebar{

            width:250px;

            height:100vh;

            position:fixed;

            left:0;

            top:0;

            background:#2c3e50;

            color:white;

        }

        .sidebar a{

            color:white;

            text-decoration:none;

            display:block;

            padding:12px 20px;

        }

        .sidebar a:hover{

            background:#34495e;

        }

        .content{

            margin-left:250px;

            padding:25px;

            overflow:auto;

            height:100vh;

        }

        .submenu{

            margin-left:15px;

            font-size:14px;

        }

        .card-header{
    font-size:18px;
    letter-spacing:.5px;
}

.table th{
    font-weight:600;
    color:#555;
    white-space:nowrap;
}

.table td{
    vertical-align:middle;
}

.card-footer{
    padding:15px 20px;
}

.badge{
    font-size:14px;
}

.card-header{
    font-size:16px;
    font-weight:600;
}

.table th{
    width:160px;
    color:#555;
    font-weight:600;
}

.table td{
    vertical-align:middle;
}

.card{
    border-radius:10px;
}

.badge{
    font-size:14px;
}




#offcanvasObat{
    width:95vw;
}

#offcanvasObat .offcanvas-body{
    height:calc(100vh - 65px);
    overflow:hidden;
}

#offcanvasObat .col-lg-5,
#offcanvasObat .col-lg-7{
    height:100%;
}

#offcanvasObat .col-lg-5{
    overflow-y:auto;
}

#offcanvasObat .col-lg-7{
    overflow-y:auto;
}
.btn-action{
    width:32px;
    height:32px;
    padding:0;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    border-radius:6px;
}

.btn-action i{
    font-size:15px;
}

.table-lplpo{
    overflow-x:auto;
    overflow-y:auto;
    max-height:600px;
}

.table-lplpo table{
    min-width:2200px;
}

.table-lplpo thead th{
    white-space:nowrap;
    position:sticky;
    top:0;
    z-index:20;
    background:#198754;
    color:#fff;
}

.table-lplpo td,
.table-lplpo th{
    white-space:nowrap;
}

.group-program td{
    background:#dbeafe;
    color:#0f172a;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.5px;
    padding:10px 15px;
    border-top:2px solid #0d6efd;
}

.table-responsive{
    overflow-x:auto;
}

.table{
    min-width:180px;
}

.table th{
    white-space:nowrap;
    text-align:center;
    vertical-align:middle;
}

.table td{
    white-space:nowrap;
    vertical-align:middle;
}

.table-primary td{
    font-weight:600;
    background:#eaf3ff;
}

.table-responsive{
    overflow-x:auto;
}




#tblVerification{
    width:100% !important;
    table-layout:fixed;
}

#tblVerification th,
#tblVerification td{
    white-space:nowrap;
    vertical-align:middle;
}

#tblVerification th:nth-child(2),
#tblVerification td:nth-child(2){
    width:140px;
}

#tblVerification th:nth-child(3),
#tblVerification td:nth-child(3){
    width:400px;
}

#tblVerification th:last-child,
#tblVerification td:last-child{
    width:70px;
    text-align:center;
}

    </style>

</head>

<body>

<div class="sidebar">
    @include('newlplpo.layouts.sidebar')
</div>

<div class="content">
    @yield('content')
</div>

@stack('offcanvas')


    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="{{ asset('bs538/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@stack('script')

</body>

</html>
