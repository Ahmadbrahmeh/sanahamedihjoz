@extends('layouts.manager')

@section('pageTitle', 'الاعدادات - تحديث كلمة المرور')

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('css/manager/setting.css') }}"> 

@stop

@section('content')
    <h1 class="h3 mb-4 text-gray-800 main-title"> كلمة المرور </h1>
    <div class="row">
        <div class="col-lg-12 main-panel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">تحديث  كلمة المرور</h6>
                </div>
                <div class="card-body">
                    <div class="container">
                        <ul class="nav nav-pills nav-fill navtop setting-tab">
                            <li class="nav-item">
                               <a class="nav-link" href="/manager/settings/personal-info">تحديث المعلومات الشخصية</a>
                            </li>
                            @if(auth()->user()->manager()->type == 1)
                            <li class="nav-item">
                                <a class="nav-link " href="/manager/settings/organization-info">تحديث بيانات المؤسسة</a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link active" href="/manager/settings/change-password" >تحديث كلمة المرور</a>
                            </li>
                        </ul>
                        <div class="tab-content float-right col-md-12">
                            <form action="{{ route('change-password') }}" class="form-admin" id="settings-form" method="post">
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
                                                <label for="password_confirmation" class="lbl">كلمة المرور الجديدة</label>
                                                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" data-validation="length" data-validation-length="min8">
                                            </div>
                                        </div>                                
                                    </div>                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password" class="lbl">تأكيد كلمة المرور الجديدة</label>
                                                <input id="password" type="password" name="password" class="form-control" data-validation="confirmation">
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
        </div>
    </div>
@stop


@section('scripts')
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