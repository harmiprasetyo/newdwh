<nav class="navbar navbar-expand-md navbar-dark sticky-top bg-success">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">MNCH Dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav me-auto mb-2 mb-md-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page"  id="home" href="/home">Data Ibu</a>
        </li>
        <li class="nav-item">
          <a class="nav-link"  id="anak" href="/home/anak">Data Anak</a>
        </li>

         <li class="nav-item">
          <a class="nav-link"  id="rme" href="/datarme">Data RME</a>
        </li>

        <li class="nav-item">
          <a class="btn btn-success"  id="rme" href="#" id="btnlogout" onclick="logoutpage()">Logout</a>
        </li>

      </ul>
      <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>

<script>

    function logoutpage(){
        $.ajax({
    url: "/logout",
    type: "POST",
    data: {
        _token: $('meta[name="csrf-token"]').attr('content')
    },
    success: function(){
        window.location.href = "/login";
    }
});
    }
    </script>
