@extends('layouts.admin')

@section('pageTitle', 'اضافة اماكن جغرافية')

@section('styles')
@stop

@section('content')
    <span id="address-lookup-url" hidden>{{ route('address-lookup', ['code' => $code]) }}</span>
    <h3 class="mt-4 main-title">ادارة العناوين جغرافية</h3>
    <ol class="breadcrumb col-md-12 mb-4">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"> الرئيسية </a></li>
        <li class="breadcrumb-item"><a href="{{ route('address-lookup') }}"> العناوين الجغرافية </a></li>
        @foreach($breadcrumbs as $breadcrumb)
        <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
            <a href="/admin/address/lookup?code={{ $breadcrumb->code }}"> {{ $breadcrumb->name }} </a>
        </li>
        @endforeach
        <li class="breadcrumb-item active">  اضافة {{ $title }} </li>
    </ol>
    <div class="card mb-4">
        @if(isset($address))
        <div class="card-header main-panel">اضافة {{ $title }} في {{ $address->title . " " . $address->name }}</div>
        @else
        <div class="card-header main-panel">اضافة مدينة</div>
        @endif
        <div class="card-body">
            <form action="{{ route('address-add') }}" class="form-admin" id="addAddressForm" method="post">
                @csrf
                <div class="row col-md-12 table_direction">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name" class="lbl">اسم ال{{ $title }}</label>
                                    <input id="name" type="text" name="name" class="form-control"  value="{{ Request::old('name') }}" data-validation="required" >
                                    @if ($errors->has('name'))
                                    <span class="help-block form-error">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>                
                        </div>
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group {{ $errors->has('code') ? ' has-error' : '' }}">
                                    <label for="code" class="lbl"> كود ال{{ $title }}</label>
                                    <input id="code" type="text" name="code" class="form-control" value="{{ Request::old('code') }}"   data-validation="required custom" data-validation-regexp="^([a-z]+)$" data-validation-error-msg="الرجاء ادخال الاحرف باللغة الانجليزية">
                                    @if ($errors->has('code'))
                                    <span class="help-block form-error">{{ $errors->first('code') }}</span>
                                    @endif
                                </div>
                            </div>                
                        </div>
                        <div class="row">
                            <div class="form-group col-md-5 col-sm-3 col-xs-12 right">
                                <button type="submit" class="btn btn-success btn-block">اضافة</button>
                            </div>
                            <div class="form-group col-md-5 col-sm-3 col-xs-12 right">
                                <button type="button" class="btn btn-secondary btn-block" onclick="navigateTo($('#address-lookup-url').text())">الغاء</button>
                            </div>
                        </div>
                        <input type="hidden" name="parent_code" value="{{ $code }}" />
                    </div>                
                </div>
            </form>                             
        </div>
    </div>    
@stop

@section('scripts')
<script>
    validateForm("addAddressForm");

    @if (session('success') == 'true')
        SuccessAlert("{{ session('message') }}");
        scrollPageTop();
    @elseif(session('success') == 'false')
        ErrorAlert("{{ session('message') }}");
        scrollPageTop();
    @endif
</script>
@stop