<!DOCTYPE html>
<html lang="id">

@include('partials.headsection')

<body>

    @include('partials.navbar')

    <main class="rme-main">

        @yield('container')

    </main>


    @stack('styles');
@stack('scripts')

</body>

</html>
