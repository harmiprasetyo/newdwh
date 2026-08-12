<div class="sidebar p-3">
    <h4>⚡ Admin</h4>
    <hr>


    <a href="/homepage" class="menu-toggle">

    <i class="fa fa-home"></i> Beranda
</a>

<a href="#" class="menu-toggle
{{ request()->is('adminpanel/user*') ? 'active' : '' }}">
    <i class="fa fa-users"></i> User Management
</a>

<div class="submenu
{{ request()->is('adminpanel/users*') ? 'd-block' : '' }}">

    <a href="/adminpanel/users"
       class="{{ request()->is('adminpanel/users') ? 'active' : '' }}">
       Daftar User
    </a>

    <a href="/adminpanel/usergroups"
       class="{{ request()->is('adminpanel/usergroups') ? 'active' : '' }}">
       Group User
    </a>



</div>

    <a href="#" class="menu-toggle
{{ request()->is('adminpanel/provinsi*','adminpanel/kota*','adminpanel/kecamatan*','adminpanel/desa*') ? 'active' : '' }}">
    <i class="fa fa-map"></i> Data Wilayah
</a>

<div class="submenu
{{ request()->is('adminpanel/provinsi*','adminpanel/kota*','adminpanel/kecamatan*','adminpanel/desa*') ? 'd-block' : '' }}">

    <a href="/adminpanel/provinsi" class="{{ request()->is('adminpanel/provinsi*') ? 'active' : '' }}">Propinsi</a>
    <a href="/adminpanel/kota" class="{{ request()->is('adminpanel/kota*') ? 'active' : '' }}">Kota</a>
    <a href="/adminpanel/kecamatan" class="{{ request()->is('adminpanel/kecamatan*') ? 'active' : '' }}">Kecamatan</a>
    <a href="/adminpanel/desa" class="{{ request()->is('adminpanel/desa*') ? 'active' : '' }}">Desa</a>

</div>




<a href="#" class="menu-toggle
{{ request()->is('adminpanel/typefaskes*','adminpanel/faskes*') ? 'active' : '' }}">
    <i class="fa fa-hospital"></i> Data Faskes
</a>

<div class="submenu
{{ request()->is('adminpanel/typefaskes*','adminpanel/faskes*') ? 'd-block' : '' }}">

    <a href="/adminpanel/typefaskes"
       class="{{ request()->is('adminpanel/typefaskes*') ? 'active' : '' }}">
       Type Faskes
    </a>

    <a href="/adminpanel/faskes"
       class="{{ request()->is('adminpanel/faskes*') ? 'active' : '' }}">
       Master Faskes
    </a>
      <a href="/adminpanel/posyandu" class="{{ request()->is('adminpanel/posyandu*') ? 'active' : '' }}">Master Posyandu</a>



</div>
<a href="#" class="menu-toggle
{{ request()->is('adminpanel/posyandu/wilayah-kerja') ? 'active' : '' }}">
    <i class="fa fa-map"></i> Wilayah Kerja
</a>
<div class="submenu
{{ request()->is('adminpanel/posyandu/wilayah-*') ? 'd-block' : '' }}">

 <a href="/adminpanel/posyandu/wilayah-kerja" class="{{ request()->is('adminpanel/posyandu/wilayah-*') ? 'active' : '' }}">Wilayah Kerja Posyandu</a>


</div>



<a href="#" class="menu-toggle
{{ request()->is('adminpanel/master/target*') ? 'active' : '' }}">
    <i class="fa fa-map"></i> Data Target
</a>
<div class="submenu
{{ request()->is('adminpanel/master/target*','adminpanel/faskes*') ? 'd-block' : '' }}">

  <a href="{{ route('master.target-sasaran.index') }}" class="{{ request()->is('adminpanel/master/target*') ? 'active' : '' }}">Target Sasaran</a>
</div>

    <a href="#" class="menu-toggle
{{ request()->is('adminpanel/masterobat*','adminpanel/parameterperingatan*','adminpanel/label-lplpo*') ? 'active' : '' }}">
    <i class="fa fa-map"></i> Master Data LPLPO
</a>
<div class="submenu
{{ request()->is('adminpanel/masterobat*','adminpanel/parameterperingatan*','adminpanel/label-lplpo*') ? 'd-block' : '' }}">

    <a href="/adminpanel/masterobat"
       class="{{ request()->is('adminpanel/masterobat*') ? 'active' : '' }}">
       Master Obat
    </a>

    <a href="/adminpanel/parameterperingatan"
       class="{{ request()->is('adminpanel/parameterperingatan*') ? 'active' : '' }}">
       Peringatan data
    </a>

     <a href="/adminpanel/label-lplpo"
       class="{{ request()->is('adminpanel/label-lplpo*') ? 'active' : '' }}">
       Labeling Kolom LPLPO
    </a>

</div>

  <a href="/logout">
      <i class="fa fa-lock"></i> Logout
    </a>

</div>




</div>
</div>
