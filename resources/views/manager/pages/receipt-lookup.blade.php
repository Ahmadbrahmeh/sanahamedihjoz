@extends('layouts.manager')

@section('pageTitle', 'عرض القاعات')

@section('styles')
@stop

@section('content')

@include('manager.modals.delete-modal', ['url' => route('receipt-delete')])

<span id="datatables-url" hidden>{{ route('receipt-datatable') }}</span>
<h1 class="h3 mb-4 text-gray-800 main-title">سندات القبض</h1>
    <div class="row">
        <div class="col-lg-12 main-panel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">عرض السندات</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTables-receipt" class="table table-bordered table_direction">
                            <thead>
                                <tr>
                                    <th class="no-sort">#</th>
                                    <th>رقم السند</th>
                                    <th>اسم الحاجز</th>
                                    <th>اسم القاعة</th>
									<th>تاريخ السند</th>
									<th>المقبوضات نقداً</th>
									<th>المقبوضات شيكات</th>
									<th>المقبوضات</th>
									<th>الملاحظات</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>تاريخ التعديل</th>
                                    <th class="no-sort">الأجراءات</th>
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
  <script src="{{ URL::asset('js/manager/receipt.js') }}"></script>
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