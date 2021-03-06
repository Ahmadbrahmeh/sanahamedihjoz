@extends('layouts.manager')

@section('pageTitle', 'عرض المشرفين')


@section('content')

@include('manager.modals.delete-modal', ['url' => route('moderator-delete')])

<span id="datatables-url" hidden>{{ route('moderator-datatable') }}</span>
<h1 class="h3 mb-4 text-gray-800 main-title">المشرفين</h1>
    <div class="row">
        <div class="col-lg-12 main-panel ">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">عرض المشرفين</h6>
                </div>
                <div class="row">
                    <div class="col-md-12 add-item-group">
                        <div class="form-group">
                            <button class="btn btn-success right" onclick="location.href='{{ route('moderator-add') }}'">
                                <span class="fa fa-plus"></span>&nbsp; اضافة مشرف
                            </button>
                        </div>
                    </div>                
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTables_moderator" class="table table-bordered table_direction">
                            <thead>
                                <tr>
                                    <th class="no-sort">#</th>
                                    <th>الأسم</th>
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
    </div>
@stop

@section('scripts')

  <script src="{{ URL::asset('js/manager/moderator.js') }}"></script>
@stop