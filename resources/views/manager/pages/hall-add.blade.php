@extends('layouts.manager')

@if ($action == "add")
    @section('pageTitle', 'اضافة قاعة')
@elseif ($action == "edit")
    @section('pageTitle', 'تعديل معلومات قاعة')
@endif


@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('css/manager/hall.css') }}">
@stop

@section('content')
    <h1 class="h3 mb-4 text-gray-800 main-title">القاعات</h1>
    <div class="row">
        <div class="col-lg-9 main-panel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    @if ($action == "add")
                    <h6 class="m-0 font-weight-bold text-primary">اضافة قاعة</h6>
                    @elseif ($action == "edit")
                    <h6 class="m-0 font-weight-bold text-primary">تعديل معلومات قاعة</h6>
                    @endif
                </div>
                <div class="card-body">
                    <form action="{{ $action == 'add' ? route('hall-add') : route('hall-edit', $hall->id) }}" class="form-admin" id="addHallForm" method="post">
                        @if ($action == "edit")
                            @method('put')
                        @endif
                        {{ csrf_field() }}
                        <div class="row table_direction">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="form_name" class="lbl">اسم القاعة</label>
                                            <input id="form_name" type="text" name="name" value="{{ $hall->name ?? ''}}" class="form-control" data-validation="required">
                                        </div>
                                    </div>                
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="form_name" class="lbl">السعر</label>
                                            <input id="form_name" type="number" name="price" value="{{ $hall->price ?? ''}}" class="form-control" data-validation="required">
                                        </div>
                                    </div>            
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                        <label for="form_name" class="lbl">عدد الافراد</label>
                                        <input id="form_name" type="number" name="capacity" value="{{ $hall->capacity ?? ''}}" class="form-control">
                                        </div>
                                    </div>            
                                </div>
                        <div class="row">
                            <div class="form-group col-md-5 col-sm-3 col-xs-12 right">
                            @if ($action == "add")
                                <button type="submit" class="btn btn-success btn-block" id="add">اضافة</button>
                            @elseif ($action == "edit")
                                <button type="submit" class="btn btn-success btn-block" id="save">حفظ</button>
                            @endif     
                            </div>
                            <div class="form-group col-md-5 col-sm-3 col-xs-12 right">
                                <button type="button" class="btn btn-secondary btn-block" onclick="history.back()">الغاء</button>
                            </div>
                        </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{ URL::asset('js/manager/hall.js') }}"></script>
    <script>
        validateForm("addHallForm");

        @if (session('success') == 'true')
            SuccessAlert("{{ session('message') }}");
            scrollPageTop();
        @elseif(session('success') == 'false')
            ErrorAlert("{{ session('message') }}");
            scrollPageTop();
        @endif
    </script>
@stop