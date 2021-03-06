@extends('layouts.admin')

@section('pageTitle', 'اضافة عملات')

@section('styles')
@stop

@section('content')
    <span id="datatables-url" hidden>{{ route('admin-currency-datatable') }}</span>
    <h3 class="mt-4 main-title">عرض العملات</h3>
    <ol class="breadcrumb col-md-12 mb-4">
        <li class="breadcrumb-item"><a href="#"> الرئيسية </a></li>
        <li class="breadcrumb-item"><a href="#"> العملات </a></li>
        <li class="breadcrumb-item active"> عرض العملات </li>
    </ol>
    <div class="card mb-4">
        <div class="card-header main-panel">عرض العملات</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 add-item-group">
                        <div class="form-group">
                            <button class="btn btn-success right" onclick="location.href='/admin/currency/add'">
                                <span class="fa fa-plus"></span>&nbsp; اضافة عمله
                            </button>
                        </div>
                    </div>                
                </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatables_currencies" class="table table-bordered table_direction">
                                <thead>
                                    <tr>
                                    <th class="no-sort">#</th>
                                    <th>اسم العملة</th>
                                    <th>كود العملة</th>
                                    <th>رمز العملة</th>
                                    <th>تاريخ الإنشاء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1 </td>
                                        <td>الدولار</td>
                                        <td>4</td>
                                        <td>4-2-2020</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>    
            </div>
    </div>    
@stop

@section('scripts')
    <script src="{{ URL::asset('js/admin/currency.js') }}"></script>
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