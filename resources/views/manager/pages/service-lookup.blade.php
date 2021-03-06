@extends('layouts.manager')

@section('pageTitle', 'عرض الخدمات')


@section('content')

@include('manager.modals.delete-modal', ['url' => route('service-delete')])

<span id="datatables-url" hidden>{{ route('service-datatable') }}</span>
<h1 class="h3 mb-4 text-gray-800 main-title">الخدمات</h1>
<div class="row">
    <div class="col-lg-12 main-panel ">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">عرض الخدمات</h6>
            </div>
            <div class="row">
                <div class="col-md-12 add-item-group">
                    <div class="form-group">
                        <button class="btn btn-success right" onclick="location.href='/manager/service/add'">
                          <span class="fa fa-plus"></span>&nbsp; اضافة خدمة
                        </button>
                    </div>
                </div>                
            </div>
            <div class="card-body">
              <div class="table-responsive">
                  <table id="dataTables_service" class="table table-bordered table_direction">
                    <thead>
                        <tr>
                          <th class="no-sort">#</th>
                          <th>اسم الخدمة</th>
                          <th>سعر الخدمة</th>
                          <th>وصف الخدمة</th>
                          <th>ملاحظات</th>
                          <th>تاريخ الإنشاء</th>
                          <th>تاريخ التعديل</th>
                          <th>الأجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>
</div>
@stop

@section('scripts')
  <script src="{{ URL::asset('js/manager/service.js') }}"></script>
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