@extends('layouts.manager')

@section('pageTitle', 'عرض العملات')


@section('content')

<span id="datatables-url" hidden>{{ route('currency-datatable') }}</span>
<h1 class="h3 mb-4 text-gray-800 main-title">العملات</h1>
    <div class="row">
        <div class="col-lg-12 main-panel ">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">عرض العملات</h6>
                </div>
                <div class="row">
                    <div class="col-md-12 add-item-group">
                        <div class="form-group">
                            <button class="btn btn-success right" onclick="location.href='/manager/currency/add'">
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{ URL::asset('js/manager/currency.js') }}"></script>
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