<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>Dashboard - MNCH</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
    <link  href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/5.3.5/apexcharts-legend.min.css" integrity="sha512-c+q4lJ9pAoiVNqS+1EXJ6yo6RnbGN3stU46/3OuQ8S468g6iMdj62TQU8H9UZ3I2xSy7VrY6jRDtFVtPeKAX8w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/5.3.5/apexcharts.min.css" integrity="sha512-IqtQ7LKr3He47p7HjxynmqZfN07VljNkdGyGDdDJ//f1r6bT0IEKQf2CCtSgun/pvbFlNnPDMRrMSQhmSxmSSg==" crossorigin="anonymous" referrerpolicy="no-referrer" />



     <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      body {
  padding-bottom: 20px;
}

.navbar {
  margin-bottom: 20px;
}

    </style>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/jquery-4.0.0.min.js') }}"></script>
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/5.3.5/apexcharts.min.js" integrity="sha512-dC9VWzoPczd9ppMRE/FJohD2fB7ByZ0VVLVCMlOrM2LHqoFFuVGcWch1riUcwKJuhWx8OhPjhJsAHrp4CP4gtw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>
@include('partials.navbar')
@include('partials.filter')
@include('partials.navtab')
<body class="container mt-4">
    @yield('container')

</body>







