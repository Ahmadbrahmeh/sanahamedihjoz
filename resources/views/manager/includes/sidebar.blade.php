<!-- @section('styles')
    <link rel="stylesheet" href="{{ URL::asset('css/manager/sidebar.css') }}">
@stop -->
  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
    <div class="sidebar-brand-icon rotate-n-15">
      
    </div>
    <div class="sidebar-brand-text mx-3">Ihjiz<sup></sup></div>
  </a>
  <!-- Divider -->
  <hr class="sidebar-divider my-0">
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseHome" aria-expanded="true" aria-controls="collapseHome">
      <i class="fas fa-home"></i>
      <span>الرئيسية</span>
    </a>
    <div id="collapseHome" class="collapse" aria-labelledby="headingHome" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header"></h6>
        <a class="collapse-item" href="/manager/reservation/calender"><span> التقويم </span></a>
      </div>
    </div>
  </li>
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFile" aria-expanded="true" aria-controls="collapseFile">
      <i class="fas fa-folder"></i>
      <span>الملفات</span>
    </a>
    <div id="collapseFile" class="collapse" aria-labelledby="headingFile" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header"></h6>
        <a class="collapse-item" href="/manager/customers/add"><span> اضافة زبون </span></a>
        <a class="collapse-item" href="/manager/service/add"><span> اضافة خدمة </span></a>
        <a class="collapse-item" href="/manager/employee/add"><span> اضافة موظف </span></a>
        <a class="collapse-item" href="/manager/supplier/add"><span> اضافة مورد </span></a>
      </div>
    </div>

  </li>
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBonds" aria-expanded="true" aria-controls="collapseBonds">
      <i class="far fa-clipboard"></i>
      <span>السندات</span>
    </a>
    <div id="collapseBonds" class="collapse" aria-labelledby="headingBonds" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header"></h6>
        <a class="collapse-item" href="/manager/receipt/add"><span>اضافة سند قبض</span></a>
        <a class="collapse-item" href="{{ route('receipt-payment-add') }}"><span>اضافة سند صرف</span></a>
      </div>
    </div>
  </li>
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReport" aria-expanded="true" aria-controls="collapseReport">
      <i class="fas fa-file"></i>
      <span>التقارير</span>
    </a>
    <div id="collapseReport" class="collapse" aria-labelledby="headingReport" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header"></h6>
        <a class="collapse-item" href="{{ route('report-account-lookup') }}"><span>كشف الحساب </span></a>        
        <a class="collapse-item" href="/manager/customers/lookup"><span> عرض الزبائن </span></a>
        <a class="collapse-item" href="/manager/service/lookup"><span> عرض الخدمات </span></a>
        <a class="collapse-item" href="/manager/supplier/lookup"><span> عرض الموردين </span></a>
        <a class="collapse-item" href="/manager/employee/lookup"><span> عرض الموظفين </span></a>
        
        <a class="collapse-item" data-toggle="collapse" href="#receiptTypes" aria-expanded="false" > عرض السندات
          <div class="bg-white py-2 collapse card " id="receiptTypes">
          <a class="collapse-item" href="/manager/receipts/lookup"><span> سندات القبض </span></a>
          <a class="collapse-item" href="/manager/receiptpayments/lookup" style="margin-bottom:12px;">
                <span> سندات الصرف </span></a>
          </div>
        </a>
        <a class="collapse-item" href="/manager/reservation/lookup"><span> عرض الحجوزات </span></a>
        <a class="collapse-item" href="/manager/currency/lookup"><span> عرض العملات </span></a>
        <a class="collapse-item" href="/manager/halls/lookup"><span> عرض القاعات </span></a>
      </div>
    </div>
  </li>
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSetting" aria-expanded="true" aria-controls="collapseSetting">
      <i class="fas fa-cog fa-pulse"></i>
      <span>الاعدادات</span>
    </a>
    <div id="collapseSetting" class="collapse" aria-labelledby="headingSetting" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header"></h6>
          <a class="collapse-item" href="/manager/currency/add"><span> اضافة عملات </span></a>
          <a class="collapse-item" href="/manager/currency/exchange"><span> اضافة سعر التحويل </span></a>
          <a class="collapse-item" href="/manager/halls/add"><span> اضافة قاعة </span></a>
          <a class="collapse-item" href="/manager/eventlist"><span> جدول المناسبات </span></a>
          <a class="collapse-item" href="/manager/settings/personal-info"><span> الاعدادات  </span></a>
      </div>
    </div>
  </li>  
  @if(auth()->user()->manager()->type == 1)                     
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseModerators" aria-expanded="true" aria-controls="collapseModerators">
    <i class="fas fa-user-tie"></i>
        <span>المشرفون</span>
    </a>
    <div id="collapseModerators" class="collapse" aria-labelledby="headingModerators" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header"></h6>
            <a class="collapse-item" href="/manager/moderator/add"><span> اضافة مشرف </span></a>
            <a class="collapse-item" href="/manager/moderator/lookup"><span> عرض المشرفين </span></a>    
        </div>
    </div>
  </li>
  @endif
  <hr class="sidebar-divider">
      <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>