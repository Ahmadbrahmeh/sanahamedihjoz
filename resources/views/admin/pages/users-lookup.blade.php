@extends('layouts.admin')

@section('pageTitle', 'عرض معلومات المستخدمين')

@section('styles')
@stop

@section('content')

    <span id="datatables-url" hidden>{{ route('user-datatable') }}</span>
    <h3 class="mt-4 main-title">المستخدمين</h3>
    <ol class="breadcrumb col-md-12 mb-4">
        <li class="breadcrumb-item"><a href="#"> الرئيسية </a></li>
        <li class="breadcrumb-item"><a href="/admin/users/lookup"> المستخدمين </a></li>
        <li class="breadcrumb-item active"> عرض معلومات المستخدمين  </li>
    </ol>    
    <div class="card mb-4">
        <div class="card-header main-panel">عرض معلومات المستخدمين</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 add-item-group">
                    <div class="form-group">
                        <button class="btn btn-success right" onclick="location.href='/admin/users/add'">
                            <span class="fa fa-plus"></span>&nbsp; اضافة مستخدم
                        </button>
                    </div>
                </div>                
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTables_users" class="datatable table table-bordered table_direction">
                        <thead>
                            <tr>
                                <th class="no-sort">#</th>
                                <th>الأسم</th>
                                <th>رقم الهوية</th>
                                <th>اسم المؤسسة</th>
                                <th>قطاع العمل</th>
                                <th>رقم الهاتف</th>
                                <th>الايميل</th>
                                <th>العنوان</th>
                                <th>تاريخ الإنشاء</th>
                                <th>تاريخ التعديل</th>
                                <th>الاجراءات</th>
                            </tr>
                        </thead>
                        <tbody> 
                        </tbody>
                    </table>
                </div>            
            </div>
        </div>
    </div>      
@stop

@section('scripts')
<script src="{{ URL::asset('js/admin/users.js') }}"></script>
<script>
    @if (session('success') == 'true')
        SuccessAlert("{{ session('message') }}");
        scrollPageTop();
    @elseif(session('success') == 'false')
        ErrorAlert("{{ session('message') }}");
        scrollPageTop();
    @endif
</script>
@stop