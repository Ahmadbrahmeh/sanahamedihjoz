@extends('layouts.admin')

@section('pageTitle', 'الاعدادات')

@section('styles')
<link href="{{ URL::asset('css/admin/setting.css') }}" rel="stylesheet">
@stop

@section('content')
    <h3 class="mt-4 main-title">الاعدادات</h3>
    <ol class="breadcrumb col-md-12 mb-4">
        <li class="breadcrumb-item"><a href="#"> الرئيسية </a></li>
        <li class="breadcrumb-item"><a href="/admin/settings/system"> الاعدادات </a></li>
        <li class="breadcrumb-item active">تحديث كلمة المرور</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header main-panel">تحديث كلمة المرور</div>
        <div class="card-body">
            <div class="container">
                <ul class="nav nav-pills nav-fill navtop setting-tab">
                    <li class="nav-item">
                        <a class="nav-link " href="/admin/settings/system">تحديث معلومات النظام</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/admin/settings/change-password" >تحديث كلمة المرور</a>
                    </li>
                </ul>
                <div class="tab-content float-right col-md-12">
                    <form action="{{ route('admin-change-password') }}" class="form-admin" id="settings-form" method="post">
                        {{ csrf_field() }}
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_old" class="lbl">كلمة المرور القديمة</label>
                                        <input id="password_old" type="password" name="password_old" class="form-control" data-validation="required">
                                    </div>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="lbl">كلمة المرور الجديدة</label>
                                        <input id="password" type="password" name="password_confirmation" class="form-control" data-validation="length" data-validation-length="min8">
                                    </div>
                                </div>                                
                            </div>                                    
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation" class="lbl">تأكيد كلمة المرور الجديدة</label>
                                        <input  type="password" name="password" class="form-control" data-validation="confirmation">
                                    </div>
                                </div>                                
                            </div>                                    
                            <div class="row btn_setting">
                                <div class="form-group col-md-3 col-sm-3 col-xs-12 right">
                                    <button type="submit" class="btn btn-success btn-block">تحديث</button>
                                </div>
                                <div class="form-group col-md-3 col-sm-3 col-xs-12 right">
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
    <script src="{{ URL::asset('js/fileValidator.js') }}"></script>
    <script>
        $.validate({
            modules : 'security',
            validateOnEvent: true,
            language: arabic,
            form: "#settings-form",
        });
        
        @if (session('success') == 'true')
            SuccessAlert("{{ session('message') }}");
            scrollPageTop();
        @elseif(session('success') == 'false')
            ErrorAlert("{{ session('message') }}");
            scrollPageTop();
        @endif
    </script>
@stop