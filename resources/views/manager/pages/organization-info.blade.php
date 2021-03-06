@extends('layouts.manager')

@section('pageTitle', 'الاعدادات - تحديث بيانات المؤسسة')

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('css/manager/setting.css') }}"> 
<link rel="stylesheet" href="{{ URL::asset('css/wickedpicker.css') }}">
<link href="{{ URL::asset('css/time-duration.css') }}" rel="stylesheet">

@stop

@section('content')
    <h1 class="h3 mb-4 text-gray-800 main-title"> المؤسسة </h1>
    <div class="row">
        <div class="col-lg-12 main-panel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">تحديث بيانات المؤسسة</h6>
                </div>
                <div class="card-body">
                    <div class="container">
                        <ul class="nav nav-pills nav-fill navtop setting-tab">
                            <li class="nav-item">
                                <a class="nav-link" href="/manager/settings/personal-info">تحديث المعلومات الشخصية</a>
                            </li>
                            @if(auth()->user()->manager()->type == 1)
                            <li class="nav-item">
                                <a class="nav-link active" href="/manager/settings/organization-info">تحديث بيانات المؤسسة</a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="/manager/settings/change-password" >تحديث كلمة المرور</a>
                            </li>
                        </ul>
                        <div class="tab-content float-right col-md-12">
                            <form action="{{ route('settings-organization') }}" class="form-admin" id="organization-info" method="post">
                                {{ csrf_field() }}
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name" class="lbl">اسم المؤسسة</label>
                                                <input id="organization-name" type="text" name="name" value="{{ $organization->name }}" class="form-control" data-validation="required">
                                            </div>
                                        </div>                                
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('from_time') ? ' has-error' : '' }}">
                                                <label for="from_time" class="lbl">من</label>                     
                                                <input type="text" name="from_time" id="from_time" class="timepicker form-control time {{ $errors->has('from_time') ? ' is-invalid error' : '' }}"  data-validation="required" />                                        
                                                @if ($errors->has('from_time'))
                                                    <span class="help-block form-error">{{ $errors->first('from_time') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group  {{ $errors->has('to_time') ? ' has-error' : '' }}">
                                                <label for="to_time" class="lbl">الى </label>                     
                                                <input type="text" name="to_time" id="to_time" class="timepicker form-control time {{ $errors->has('to_time') ? ' is-invalid error' : '' }}" data-validation="required"/>
                                                @if ($errors->has('to_time'))
                                                    <span class="help-block form-error">{{ $errors->first('to_time') }}</span>
                                                @endif
                                            </div>
                                        </div>            
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="currency" class="lbl">العملة الافتراضية</label>
                                                <select class="form-control" id="currency" name="currency" data-validation="required"> 
                                                @foreach ($currencies as $currency) 
                                                    <option value="{{ $currency->id }}" {{ ($organization->default_currency == $currency->id) ? "selected" : ""}}>{{ $currency->name }}</option>
                                                @endforeach  
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="preparation-time" class="lbl">وقت التحضير بين المناسبات</label>
                                                <input type="text" id="preparation" data-name-minutes="preparation_minutes" data-name-hours="preparation_hours" data-value-hours="{{ $organization->prepare_duration_hours }}" data-value-minutes="{{ $organization->prepare_duration_minutes }}">
                                            </div>
                                        </div>                               
                                    </div>                                                                     
                                    <div class="row">
                                        <div class="col-md-12 col-xs-6"><br>
                                            <div class="form-group">
                                                <label for="form_name" class="lbl">ايام العمل الرسمية</label>                     
                                            </div>
                                            <div class="form-group" >
                                                <ul id="days-on-off">
                                                    <li>
                                                        <input type="checkbox" name="saturday" id="saturday" {{ $working_days['saturday'] }}>
                                                        <label for="saturday">السبت</label>
                                                    </li>                                            
                                                    <li>
                                                        <input type="checkbox" name="sunday" id="sunday" {{ $working_days['sunday'] }}>
                                                        <label for="sunday">الأحد</label>
                                                    </li>
                                                    <li>
                                                        <input type="checkbox" name="monday" id="monday" {{ $working_days['monday'] }}>
                                                        <label for="monday">الأثنين</label>
                                                    </li>
                                                    <li>
                                                        <input type="checkbox" name="tuesday" id="tuesday" {{ $working_days['tuesday'] }}>
                                                        <label for="tuesday">الثلاثاء</label>
                                                    </li>
                                                    <li>
                                                        <input type="checkbox" name="wednesday" id="wednesday" {{ $working_days['wednesday'] }}>
                                                        <label for="wednesday">الأربعاء</label>
                                                    </li>
                                                    <li>
                                                        <input type="checkbox" name="thursday" id="thursday" {{ $working_days['thursday'] }}>
                                                        <label for="thursday">الخميس</label>                        
                                                    </li>
                                                    <li>
                                                        <input type="checkbox" name="friday" id="friday" {{ $working_days['friday'] }}>
                                                        <label for="friday">الجمعة</label>
                                                    </li>
                                                </ul>
                                            </div>                    
                                        </div>
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
        </div>
    </div>
@stop


@section('scripts')
    <script src="{{ URL::asset('js/wickedpicker.js') }}"></script>
    <script src="{{ URL::asset('js/time-duration.js') }}"></script>
    <script>
/* timepicker */
        $('#from_time').wickedpicker( {
            now: "{{ Request::old('from_time') ?? $organization->from_time }}",
            title:"من الساعة",
            timeSeparator: ":"
        });
        $('#to_time').wickedpicker( {
            now: "{{ Request::old('to_time') ?? $organization->to_time }}",
            title:"الى الساعة",
            timeSeparator: ":"
        });
    </script>
    <script>
        validateForm("organization-info");

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