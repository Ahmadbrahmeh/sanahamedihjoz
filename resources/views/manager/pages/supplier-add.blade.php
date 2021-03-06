@extends('layouts.manager')

@section('pageTitle', 'اضافة مورد')

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('css/manager/reservation.css') }}">
@stop

@section('content')
    <span id="address-list-url" hidden>/manager/address</span>
    <h1 class="h3 mb-4 text-gray-800 main-title">المورد</h1>
    <div class="row">
        <div class="col-lg-9 main-panel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">اضافة مورد</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('supplier-add') }}" class="form-admin" id="addSupplierForm" method="post">
                        {{ csrf_field() }}                 
                        <div class="row col-md-12 table_direction">
                            <div class="col-md-12">
                            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="lbl">الاسم</label>
                                <input id="name" type="text" name="name" class="form-control" value="{{ Request::old('name')  }}" data-validation="required">
                                @if ($errors->has('name'))
                                    <span class="help-block form-error">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address" class="lbl">المدينة</label>
                                        <select class="form-control" name="address_city" id="address-city" data-validation="required">
                                            <option value="">اختر مدينة</option>
                                            @foreach($cities as $city)
                                            <option value="{{ $city->code }}">{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group {{ isset($address_types['region']) ? '' : 'hide' }}" id="form-address-region">
                                        <label for="address" class="lbl">المنطقة</label>
                                        <select class="form-control" name="address_region" id="address-region" data-validation="required">
                                            <option value="">اختر منطقة</option>
                                        </select>
                                    </div>
                                </div>  
                                <div class="col-md-4">
                                    <div class="form-group {{ isset($address_types['street']) ? '' : 'hide' }}" id="form-address-street">
                                        <label for="address" class="lbl">الشارع</label>
                                        <select class="form-control" name="address_street" id="address-street">
                                            <option value="">اختر منطقة</option>
                                        </select>
                                    </div>
                                </div>        
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email" class="lbl">البريد الالكتروني</label>
                                        <input id="email" type="text" name="email" class="form-control" value="{{ Request::old('email')  }}" data-validation="email" data-validation-optional="true">
                                        @if ($errors->has('email'))
                                            <span class="help-block form-error">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>  
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('certifcate') ? ' has-error' : '' }}">
                                        <label for="certifcate" class="lbl">رقم الهوية</label>
                                        <input id="certifcate" type="text" name="certifcate" value="{{ Request::old('certifcate')  }}" class="form-control">
                                        @if ($errors->has('certifcate'))
                                            <span class="help-block form-error">{{ $errors->first('certifcate') }}</span>
                                        @endif
                                    </div>
                                </div>           
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('phone1') ? ' has-error' : '' }}">
                                        <label for="phone1" class="lbl">رقم الهاتف الاول</label>
                                        <input id="phone1" type="text" name="phone1" class="form-control" value="{{ Request::old('phone1')  }}" data-validation="required">
                                        @if ($errors->has('phone1'))
                                            <span class="help-block form-error">{{ $errors->first('phone1') }}</span>
                                        @endif
                                    </div>
                                </div>  
                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('phone2') ? ' has-error' : '' }}">
                                        <label for="phone2" class="lbl">رقم الهاتف الثاني</label>
                                        <input id="phone2" type="text" name="phone2" value="{{ Request::old('phone2')  }}" class="form-control">
                                        @if ($errors->has('phone2'))
                                            <span class="help-block form-error">{{ $errors->first('phone2') }}</span>
                                        @endif
                                    </div>
                                </div>  
                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('phone3') ? ' has-error' : '' }}">
                                        <label for="phone3" class="lbl">رقم الهاتف الثالث</label>
                                        <input id="phone3" type="text" name="phone3" value="{{ Request::old('phone3')  }}" class="form-control">
                                        @if ($errors->has('phone3'))
                                            <span class="help-block form-error">{{ $errors->first('phone3') }}</span>
                                        @endif
                                    </div>
                                </div>    
                            </div>
                            <br />
                            <div class="row">
                                <div class="form-group col-md-6 col-sm-3 col-xs-12 right">
                                    <button type="submit" class="btn btn-success btn-block">اضافة</button>
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
@stop

@section('scripts')
    <script src="{{ URL::asset('js/address.js') }}"></script>
    <script>
        validateForm("addSupplierForm");

        @if (session('success') == 'true')
            SuccessAlert("{{ session('message') }}");
            scrollPageTop();
        @elseif(session('success') == 'false')
            ErrorAlert("{{ session('message') }}");
            scrollPageTop();
        @endif
    </script>
@stop