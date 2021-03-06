@extends('layouts.manager')

@section('pageTitle', 'جدول المناسبات')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('css/manager/eventlist.css') }}">
@stop


@section('content')
@include('manager.modals.delete-modal', ['url' => route('eventlist-delete')])
<h1 class="h3 mb-4 text-gray-800 main-title">جدول المناسبات</h1>
    <div class="row">
        <div class="col-lg-12 main-panel ">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">معلومات جدول المناسبات</h6>
                </div>
                <div class="card-body">
                <form action="{{ route('eventlist-add') }}" class="form-inline" id="form-eventlist" method="post">
                        @csrf
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="question" class="label">جدول المناسبة</label>
                                    <input type="text" class="form-control col-md-12" name="name" data-validation="required">
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:20px;">
                            <button type="submit" class="btn btn-success col-md-12" style="margin-right:25px;">اضافة</button>
                        </div>
                    </form>
                    <div class="table-responsive" id="tbl-event-list">
                        <table class="table table-bordered table_direction col-md-6" id="table-eventlist">
                        <tr>
                            <th>العنوان</th>
                            <th>الإجرائات</th>
                        </tr>
                            @foreach($eventlists as $eventlist)
                            <tr>
                                <td>{{ $eventlist->name }}</td> 
                                <td><a href="#" class="btn btn-danger btn-circle " data-id="{{ $eventlist->id }}" data-toggle="modal" data-target="#deleteModal"><i class="fas fa-trash"></i></a></td>                           
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
<script src="{{ URL::asset('js/fileValidator.js') }}"></script>
<script>
    validateForm("form-eventlist");

    @if (session('success') == 'true')
        SuccessAlert("{{ session('message') }}");
        scrollPageTop();
    @elseif(session('success') == 'false')
        ErrorAlert("{{ session('message') }}");
        scrollPageTop();
    @endif
</script>

@stop