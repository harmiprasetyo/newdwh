<div class="sidebar p-3">
    <h4>⚡ Admin</h4>
    <hr>

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

</div>

</div>
