<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">الرئيسية</div>
            <!-- currencies -->
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#currencies" aria-expanded="false" aria-controls="currencies">
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                العملات
                <div class="sb-nav-link-icon"><i class="fas fa-money-bill-alt"></i></div>
            </a>
            <div class="collapse" id="currencies" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="/admin/currency/lookup">عرض العملات</a>
                    <a class="nav-link" href="/admin/currency/add">اضافة العملات</a>
                    <a class="nav-link" href="/admin/currency/exchange">اضافة سعر التحويل</a>                    
                </nav>
            </div>     
            <!-- users -->
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#users" aria-expanded="false" aria-controls="users">
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                المستخدمين
                <div class="sb-nav-link-icon"><i class="fas fa-user fa-fw"></i></div>
            </a>
            <div class="collapse" id="users" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="/admin/users/lookup">عرض المستخدمين</a>
                    <a class="nav-link" href="/admin/users/add">اضافة مستخدمين</a>
                </nav>
            </div>     
            <!-- address -->
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#manage-address" aria-expanded="false" aria-controls="address">
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                العناوين الجغرافية
                <div class="sb-nav-link-icon"><i class="fas fa-map-marker-alt"></i></div>
            </a>
            <div class="collapse" id="manage-address" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
                <a class="nav-link" href="/admin/address/lookup">ادارة العناوين</a>
            </nav>
            </div>                                 
            
        </div>
    </div>
</nav>
