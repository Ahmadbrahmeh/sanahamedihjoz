@extends('layouts.manager')

@section('pageTitle', 'عرض الموظفين')
@section('content')
<span id="datatables-url" hidden>{{ route('employee-datatable') }}</span>
<h1 class="h3 mb-4 text-gray-800 main-title">الموظفين</h1>
    <div class="row">
        <div class="col-lg-12 main-panel ">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">عرض الموظفين</h6>
                </div>
                <div class="row">
                    <div class="col-md-12 add-item-group">
                        <div class="form-group">
                            <button class="btn btn-success right" onclick="location.href='/manager/employee/add'">
                                <span class="fa fa-plus"></span>&nbsp; اضافة موظف
                            </button>
                        </div>
                    </div>                
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatables_emplyee" class="table table-bordered table_direction">
                            <thead>
                                <tr>
                                    <th class="no-sort">#</th>
                                    <th>اسم الموظف</th>
                                    <th>نوع الراتب</th>
                                    <th>الايميل</th>
                                    <th>رقم الهاتف</th>
                                    <th>العنوان</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>تاريخ التعديل</th>
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
    <script src="{{ URL::asset('js/manager/employee.js') }}"></script>
@stop