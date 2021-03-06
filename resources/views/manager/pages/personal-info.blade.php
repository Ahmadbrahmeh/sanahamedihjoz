@extends('layouts.manager')

@section('pageTitle', 'الاعدادات - تحديث المعلومات الشخصية')


@section('styles')
<link rel="stylesheet" href="{{ URL::asset('css/manager/setting.css') }}"> 

@stop

@section('content')
    <span id="address-list-url" hidden>/manager/address</span>
    <h1 class="h3 mb-4 text-gray-800 main-title"> المعلومات الشخصية </h1>
    <div class="row">
        <div class="col-lg-12 main-panel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">تحديث المعلومات الشخصية</h6>
                </div>
                <div class="card-body">
                    <div class="container">
                        <ul class="nav nav-pills nav-fill navtop setting-tab">
                            <li class="nav-item">
                                <a class="nav-link active" href="/manager/settings/personal-info">تحديث المعلومات الشخصية</a>
                            </li>
                            @if(auth()->user()->manager()->type == 1)
                            <li class="nav-item">
                                <a class="nav-link" href="/manager/settings/organization-info">تحديث بيانات المؤسسة</a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="/manager/settings/change-password" >تحديث كلمة المرور</a>
                            </li>
                        </ul>
                        <div class="tab-content float-right col-md-12">
                            <form action="{{ route('settings-personal') }}" class="form-admin" id="personal-info" method="post">
                                {{ csrf_field() }}
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fname" class="lbl">الاسم الاول </label>
                                                <input type="text" name="fname" value="{{ $user->fname }}" class="form-control" data-validation="required">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="lname" class="lbl">الاسم الأخير </label>
                                                <input  type="text" name="lname" value="{{ $user->lname }}" class="form-control" data-validation="required">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="address" class="lbl">المدينة</label>
                                                <select class="form-control" name="address_city" id="address-city" data-validation="required">
                                                    <option value="">اختر مدينة</option>
                                                    @foreach($cities as $city)
                                                    <option {{ ($address_types['city']->code == $city->code ? "selected": "") }} value="{{ $city->code }}">{{ $city->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group {{ isset($address_types['region']) ? '' : 'hide' }}" id="form-address-region">
                                                <label for="address" class="lbl">المنطقة</label>
                                                <select class="form-control" name="address_region" id="address-region" data-validation="required">
                                                <option value="">اختر منطقة</option>
                                                @foreach($regions as $region)
                                                <option {{ ( $address_types['region']->code == $region->code ? "selected": "")  }} value="{{ $region->code }}">{{ $region->name }}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>  
                                        <div class="col-md-4">
                                            <div class="form-group {{ isset($address_types['street']) ? '' : 'hide' }}" id="form-address-street">
                                                <label for="address" class="lbl">الشارع</label>
                                                <select class="form-control" name="address_street" id="address-street">
                                                <option value="">اختر منطقة</option>
                                                @foreach($streets as $street)
                                                <option {{ ( $address_types['street']->code == $street->code ? "selected": "")  }} value="{{ $street->code }}">{{ $street->name }}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>        
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fb-link" class="lbl">عنوان الفيس بوك</label>
                                                <input id="fb-link" type="text" value="{{ $user->fb_link }}" name="fb_link" class="form-control" data-validation="url" data-validation-optional="true">
                                            </div>                  
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email" class="lbl">البريد الالكتروني</label>
                                                <input id="email" type="text" value="{{ $user->email }}" disabled name="email" class="form-control" data-validation="required email">                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="phone1" class="lbl">رقم الهاتف الاول</label>
                                                <input id="phone1" type="text" value="{{ $user->phone1 }}" name="phone1" class="form-control" data-validation="required">
                                            </div>
                                        </div>  
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="phone2" class="lbl">رقم الهاتف الثاني</label>
                                                <input id="phone2" type="text" value="{{ $user->phone2 }}" name="phone2" class="form-control">
                                            </div>
                                        </div>  
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="phone3" class="lbl">رقم الهاتف الثالث</label>
                                                <input id="phone3" type="text" value="{{ $user->phone3 }}" name="phone3" class="form-control">
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
    <script src="{{ URL::asset('js/address.js') }}"></script>
    <script>
        validateForm("personal-info");
        
        @if (session('success') == 'true')
            SuccessAlert("{{ session('message') }}");
            scrollPageTop();
        @elseif(session('success') == 'false')
            ErrorAlert("{{ session('message') }}");
            scrollPageTop();
        @endif
    </script>
@stop