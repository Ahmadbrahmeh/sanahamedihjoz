@extends('layouts.admin')

@section('pageTitle', 'اضافة عملة')

@section('styles')
<link href="{{ URL::asset('css/admin/currency.css') }}" rel="stylesheet">
@stop

@section('content')
    <h3 class="mt-4 main-title">العملات</h3>
    <ol class="breadcrumb col-md-12 mb-4">
        <li class="breadcrumb-item"><a href="#"> الرئيسية </a></li>
        <li class="breadcrumb-item"><a href="/admin/currency/lookup"> العملات </a></li>
        <li class="breadcrumb-item active"> اضافة عملة</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header main-panel">اضافة عملة</div>
        <div class="card-body">
            <form action="{{ route('admin-currency-add') }}" class="form-admin" id="addCurrenciesForm" method="post">
                {{ csrf_field() }}
                <div class="row col-md-12 table_direction">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="name" class="lbl">اسم العملة</label>
                                    <input id="name" type="text" name="name" class="form-control"  data-validation="required" >
                                </div>
                            </div>                
                        </div>
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="code" class="lbl">كود العملة</label>
                                    <input id="code" type="text" name="code" class="form-control"   data-validation="required" >
                                </div>
                            </div>                
                        </div>
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="sign" class="lbl">رمز العملة</label>
                                    <input id="sign" type="text" name="sign" class="form-control" data-validation="required" >
                                </div>
                            </div>          
                        </div>
                        <br />
                        <div class="row">
                            <div class="form-group col-md-5 col-sm-3 col-xs-12 right">
                                <button type="submit" class="btn btn-success btn-block">اضافة</button>
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
@stop

@section('scripts')
<script>
    validateForm("addCurrenciesForm");

    @if (session('success') == 'true')
        SuccessAlert("{{ session('message') }}");
        scrollPageTop();
    @elseif(session('success') == 'false')
        ErrorAlert("{{ session('message') }}");
        scrollPageTop();
    @endif
</script>
@stop