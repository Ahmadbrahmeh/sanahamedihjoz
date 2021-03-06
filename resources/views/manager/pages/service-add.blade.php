@extends('layouts.manager')

@if ($action == "add")
    @section('pageTitle', 'اضافة خدمة')
@elseif ($action == "edit")
    @section('pageTitle', 'تعديل معلومات خدمة')
@endif

@section('styles')
@stop

@section('content')
    <h1 class="h3 mb-4 text-gray-800 main-title"> الخدمات </h1>
    <div class="row">
        <div class="col-lg-12 main-panel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    @if ($action == "add")
                    <h6 class="m-0 font-weight-bold text-primary">اضافة خدمة</h6>
                    @elseif ($action == "edit")
                    <h6 class="m-0 font-weight-bold text-primary">تعديل معلومات خدمة</h6>
                    @endif
                </div>
                <div class="card-body">
                    <form action="{{ $action == 'add' ? route('service-add') : route('service-edit', $service->id) }}" class="form-admin" id="addServiceForm" method="post">
                        @if ($action == "edit")
                            @method('put')
                        @endif
                        {{ csrf_field() }}
                        <div class="row col-md-12 table_direction">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="service_name" class="lbl">اسم الخدمة</label>
                                            <input id="service-name" type="text" name="name" class="form-control" value="{{ $service->name ?? ''}}" data-validation="required" >
                                        </div>
                                    </div>                
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="price" class="lbl"> السعر </label>
                                            <input id="price" type="number" name="price"  min="1" class="form-control" value="{{ $service->price ?? ''}}" data-validation="required" >
                                        </div>
                                    </div>          
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="description" class="lbl"> وصف الخدمة </label>
                                            <input id="description" type="text" name="description" class="form-control" value="{{ $service->description ?? ''}}">
                                        </div>
                                    </div>          
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="notes" class="lbl"> الملاحظات  </label>
                                            <input id="notes" type="text" name="notes" class="form-control" value="{{ $service->notes ?? ''}}">
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
    <script>
        validateForm("addServiceForm");
        
        @if (session('success') == 'true')
            SuccessAlert("{{ session('message') }}");
            scrollPageTop();
        @elseif(session('success') == 'false')
            ErrorAlert("{{ session('message') }}");
            scrollPageTop();
        @endif
    </script>
@stop