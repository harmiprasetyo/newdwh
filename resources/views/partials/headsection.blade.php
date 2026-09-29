<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        http-equiv="X-UA-Compatible"
        content="ie=edge"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>Dashboard - MNCH</title>

    {{-- Bootstrap --}}
    <link
        href="{{ asset('css/bootstrap.min.css') }}"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    {{-- SweetAlert --}}
    <link
        href="{{ asset('css/sweetalert2.min.css') }}"
        rel="stylesheet"
    >

    {{-- DataTables --}}
    <link
        rel="stylesheet"
        href="https://cdn.datatables.net/2.3.5/css/dataTables.dataTables.min.css"
    >

    {{-- ApexCharts --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/5.3.5/apexcharts-legend.min.css"
        integrity="sha512-c+q4lJ9pAoiVNqS+1EXJ6yo6RnbGN3stU46/3OuQ8S468g6iMdj62TQU8H9UZ3I2xSy7VrY6jRDtFVtPeKAX8w=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/5.3.5/apexcharts.min.css"
        integrity="sha512-IqtQ7LKr3He47p7HjxynmqZfN07VljNkdGyGDdDJ//f1r6b0TIEKQf2CCtSgun/pvbFlNnPDMRrMSQhmSxmSSg=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    >

    {{-- NProgress --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css"
    >

    {{-- RME CSS --}}
    <link
        rel="stylesheet"
        href="{{ mix('css/rme/datapasien.css') }}"
    >
</head>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('js/jquery-4.0.0.min.js') }}"></script>

<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>

<script
    src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"
    integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
></script>

{{-- DataTables --}}
<script src="https://cdn.datatables.net/2.3.5/js/dataTables.min.js"></script>

{{-- ApexCharts --}}
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/5.3.5/apexcharts.min.js"
    integrity="sha512-dC9VWzoPczd9ppMRE/FJohD2fB7ByZ0VVLVCMlOrM2LHqoFFuVGcWch1riUcwKJuhWx8OhPjhJsAHrp4CP4gtw=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
></script>

{{-- NProgress --}}
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"
></script>

{{-- RME JS --}}
<script src="{{ mix('js/rme/datapasien.js') }}"></script>
