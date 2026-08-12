@include('partials.admin.adminheader')

<body>

<!-- SIDEBAR -->
@include('partials.admin.adminsidebar')

<!-- CONTENT -->
<div class="content">

    <!-- TOPBAR -->
    <div class="topbar">
        <!-- <h5 class="mb-0"></h5> -->
    </div>

    <div class="p-4">
        @yield('content')
    </div>

</div>

<!-- JS -->


<script>
$('.menu-toggle').click(function(){
    $(this).next('.submenu').slideToggle();
});
</script>
@stack('styles');
@stack('scripts')
</body>
</html>
