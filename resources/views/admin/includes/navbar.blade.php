<a class="navbar-brand" href="#">نظام احجز (الادمن)</a>
<button class="btn btn-link btn-sm order-1 order-lg-0 " id="sidebarToggle" href="#">
    <i class="fas fa-bars"></i>
</button>
<!-- Navbar Search-->
<form class="search-form d-none d-md-inline-block form-inline ml-auto mr-0 my-2 my-md-0">
    <div class="input-group">
        <div class="input-group-append">
            <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
        </div>
        <input class="form-control input-serach" type="text" placeholder="... ابحث" aria-label="Search" aria-describedby="basic-addon2" />
    </div>
</form>
<!-- END  Navbar Search -->
<ul class="navbar-dropdown navbar-nav ml-auto ml-md-0">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
            <a class="dropdown-item" href="/admin/settings/system">اعدادات</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">تسجيل الخروج</a>
        </div>
    </li>
</ul>