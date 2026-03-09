<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-success">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">MNCH Dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav me-auto mb-2 mb-md-0">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="home">Data Ibu</a>
        </li>
        <li class="nav-item">
          <a class="nav-link dropdown-toggle active" href="/home/anak" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Data Anak
          </a>
           <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item" href="/home/anakimd">Anak KN dan IMD</a></li>
            <li><a class="dropdown-item" href="/home/anakmk">Manajemen Kasus</a></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>




      </ul>
      <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
