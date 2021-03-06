<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
<i class="fa fa-bars"></i>
</button>


<ul class="navbar-nav mr-auto">
<!-- search bar mobile mode -->
    <li class="nav-item dropdown no-arrow d-sm-none">
        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-search fa-fw"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
        <form class="form-inline mr-auto w-100 navbar-search">
            <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
            </div>
        </form>
        <!-- end search bar mobile mode -->
        </div>
    </li>
    <li class="nav-item dropdown no-arrow ml-3">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name }}</span>
        <img class="img-profile rounded-circle" src="{{ URL::asset('images/default_img_user.png') }}"  >
        </a>
        <div class="dropdown-menu dropdown-menu-left shadow animated--grow-in" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="/manager/settings/personal-info">
            <i class="fas fa-cogs fa-sm fa-fw ml-2 text-gray-400"></i>
            اعدادات
        </a>
        
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
            تسجيل الخروج
        </a>
        </div>
    </li>
    <div class="topbar-divider d-none d-sm-block"></div>
</ul>

<form class="d-none d-sm-inline-block form-inline ml-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
    <h5 id="organization-name" style="color:#385ece;">{{ auth()->user()->manager()->organization()->name }}</h5>
</form>
<!-- search bar -->
<form class="d-none d-sm-inline-block form-inline ml-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
<div class="input-group">
    <input type="text" class="form-control bg-light border-0 small" placeholder="... ابحث عن" id="serach-header" aria-label="Search" aria-describedby="basic-addon2">
    <div class="input-group-append">
    <button class="btn btn-primary" type="button">
        <i class="fas fa-search fa-sm"></i>
    </button>
    </div>    
</div>
</form>
<!-- end search bar -->
