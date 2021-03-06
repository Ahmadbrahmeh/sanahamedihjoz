@extends('layouts.manager')

@section('pageTitle', 'عرض المناسبات')


@section('content')
<h1 class="h3 mb-4 text-gray-800 main-title">المناسبات</h1>
    <div class="row">
        <div class="col-lg-12 main-panel ">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">عرض المناسبات</h6>
                </div>
                <div class="row">
                    <div class="col-md-12 add-item-group">
                        <div class="form-group">
                            <button class="btn btn-success right" onclick="location.href='/manager/event/add'">
                                <span class="fa fa-plus"></span>&nbsp; اضافة مناسبه
                            </button>
                        </div>
                    </div>                
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTables_event" class="table table-bordered table_direction">
                            <thead>
                                <tr>
                                    <th class="no-sort">#</th>
                                    <th>Test</th>
                                    <th>Test</th>
                                    <th>Test</th>
                                    <th>Test</th>
                                    <th>Test</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>1</th>
                                    <th>Test</th>
                                    <th>Test</th>
                                    <th>Test</th>
                                    <th>Test</th>
                                    <th>Test</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
  <script src="{{ URL::asset('js/manager/event.js') }}"></script>
@stop