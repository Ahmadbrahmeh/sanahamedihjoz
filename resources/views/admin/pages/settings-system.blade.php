@extends('layouts.admin')

@section('pageTitle', 'الاعدادات')

@section('styles')
<link href="{{ URL::asset('css/admin/setting.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/time-duration.css') }}" rel="stylesheet">
@stop

@section('content')
    <h3 class="mt-4 main-title">الاعدادات</h3>
    <ol class="breadcrumb col-md-12 mb-4">
        <li class="breadcrumb-item"><a href="#"> الرئيسية </a></li>
        <li class="breadcrumb-item"><a href="/admin/settings/system"> الاعدادات </a></li>
        <li class="breadcrumb-item active"> اعدادات النظام</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header main-panel">تحديث معلومات النظام</div>
        <div class="card-body">
            <div class="container">
                <ul class="nav nav-pills nav-fill navtop setting-tab">
                    <li class="nav-item">
                        <a class="nav-link active" href="/admin/settings/system">تحديث معلومات النظام</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="/admin/settings/change-password" >تحديث كلمة المرور</a>
                    </li>
                </ul>
                <div class="tab-content float-right col-md-12">
                    <form action="{{ route('settings-system') }}" class="form-admin" id="settings-form" method="post">
                        {{ csrf_field() }}
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="currency" class="lbl">العملة الافتراضية</label>
                                        <select class="form-control" id="currency" name="currency" data-validation="required"> 
                                        @foreach ($currencies as $currency) 
                                            <option value="{{ $currency->id }}" {{ ($settings->default_currency == $currency->id) ? "selected" : ""}}>{{ $currency->name }}</option>
                                        @endforeach  
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label for="preparation-time" class="lbl">الوقت الافتراضي للتحضير قبل الحجوزات</label>
                                        <input type="text" id="preparation" data-name-minutes="preparation_minutes"  data-name-hours="preparation_hours" data-value-hours="{{ $settings->prepare_duration_hours }}" data-value-minutes="{{ $settings->prepare_duration_minutes }}">
                                    </div>
                                </div>                   
                            </div>
                            <div class="row">                                                             
                            </div>                                                
                            <div class="row btn_setting">
                                <div class="form-group col-md-6 col-sm-3 col-xs-12 right">
                                    <button type="submit" class="btn btn-success btn-block">تحديث</button>
                                </div>
                                <div class="form-group col-md-6 col-sm-3 col-xs-12 right">
                                    <button type="button" class="btn btn-secondary btn-block" onclick="history.back()">الغاء</button>
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
    <script src="{{ URL::asset('js/time-duration.js') }}"></script>
    <script>
        $("#preparation").durationPicker();
    
        @if (session('success') == 'true')
            SuccessAlert("{{ session('message') }}");
            scrollPageTop();
        @elseif(session('success') == 'false')
            ErrorAlert("{{ session('message') }}");
            scrollPageTop();
        @endif
    </script>
@stop