<div class="sidebar p-3">
    <h4>⚡ Admin</h4>
    <hr>


    <a href="/homepage" class="menu-toggle">

    <i class="fa fa-home"></i> Beranda
</a>

<a href="#" class="menu-toggle
{{ request()->is('adminpanel/userpanel/*') ? 'active' : '' }}">
    <i class="fa fa-users"></i> User Management
</a>

<div class="submenu
{{ request()->is('adminpanel/userpanel/users*') ? 'd-block' : '' }}">

    <a href="/adminpanel/userpanel/users"
       class="{{ request()->is('adminpanel/userpanel/users') ? 'active' : '' }}">
       Daftar User
    </a>

    <a href="/adminpanel/userpanel/groups"
       class="{{ request()->is('adminpanel/userpanel/groups') ? 'active' : '' }}">
       Group User
    </a>

     <a href="/adminpanel/userpanel/roles"
       class="{{ request()->is('adminpanel/userpanel/roles') ? 'active' : '' }}">
       User Role
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
{{ request()->is('adminpanel/typefaskes*','adminpanel/master/*','adminpanel/posyandu*') ? 'active' : '' }}">
    <i class="fa fa-hospital"></i> Data Faskes
</a>

<div class="submenu
{{ request()->is('adminpanel/typefaskes*','adminpanel/master/faskes*','adminpanel/posyandu*') ? 'd-block' : '' }}">

    <a href="/adminpanel/typefaskes"
       class="{{ request()->is('adminpanel/typefaskes*') ? 'active' : '' }}">
       Type Faskes
    </a>

    <a href="/adminpanel/master/faskes"
       class="{{ request()->is('adminpanel/master/faskes*') ? 'active' : '' }}">
       Master Faskes
    </a>
      <a href="/adminpanel/posyandu" class="{{ request()->is('adminpanel/posyandu*') ? 'active' : '' }}">Master Posyandu</a>



</div>
<a href="#" class="menu-toggle
{{ request()->is('adminpanel/posyandu/wilayah-kerja','adminpanel/wilayahkerja/puskesmas') ? 'active' : '' }}">
    <i class="fa fa-map"></i> Wilayah Kerja
</a>
<div class="submenu
{{ request()->is('adminpanel/posyandu/wilayah-*','adminpanel/wilayahkerja/puskesmas*') ? 'd-block' : '' }}">

 <a href="/adminpanel/posyandu/wilayah-kerja" class="{{ request()->is('adminpanel/posyandu/wilayah-*') ? 'active' : '' }}">Wilayah Kerja Posyandu</a>


</div>


<div class="submenu
{{ request()->is('adminpanel/wilayahkerja/puskesmas*') ? 'd-block' : '' }}">

 <a href="/adminpanel/wilayahkerja/puskesmas" class="{{ request()->is('adminpanel/wilayahkerja/puskesmas*') ? 'active' : '' }}">Wilayah Kerja Puskesmas</a>


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

<a href="{{ route('activity-log.index') }}">

    <i class="fas fa-history me-2"></i>

    Activity Log

</a>


  <a href="/logout">
      <i class="fa fa-lock"></i> Logout
    </a>

</div>




</div>
</div>
