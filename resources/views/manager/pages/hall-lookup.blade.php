@extends('layouts.manager')

@section('pageTitle', 'عرض القاعات')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('css/manager/hall.css') }}"> 
@stop

@section('content')

@include('manager.modals.delete-modal', ['url' => route('hall-delete')])

<span id="datatables-url" hidden>{{ route('hall-datatable') }}</span>
<h1 class="h3 mb-4 text-gray-800 main-title">القاعات</h1>
    <div class="row">
        <div class="col-lg-12 main-panel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">عرض القاعات</h6>
                </div>
                <div class="row">
                    <div class="col-md-12 add-item-group">
                        <div class="form-group">
                            <button class="btn btn-success right" onclick="location.href='/manager/halls/add'">
                                <span class="fa fa-plus"></span>&nbsp; اضافة قاعة
                            </button>
                        </div>
                    </div>                
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTables-hall" class="table table-bordered table_direction">
                            <thead>
                                <tr>
                                    <th class="no-sort">#</th>
                                    <th>اسم القاعة</th>
                                    <th>سعر القاعة</th>
                                    <th>سعة القاعة</th>
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
  <script src="{{ URL::asset('js/manager/hall.js') }}"></script>
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