@extends('layouts.admin')

@section('pageTitle', 'اضافة عملات')

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('css/admin/currency.css') }}">
@stop

@section('content')
    <span id="getExchangeRate" hidden>{{ route('admin-currency-exchange-get', '') }}</span>
    <h3 class="mt-4 main-title">العملات</h3>
    <ol class="breadcrumb col-md-12 mb-4">
        <li class="breadcrumb-item"><a href="#"> الرئيسية </a></li>
        <li class="breadcrumb-item"><a href="/admin/currency/lookup"> العملات </a></li>
        <li class="breadcrumb-item active"> اضافة سعر الصرف </li>
   </ol>
    <div class="card mb-4">
        <div class="card-header main-panel">اضافة سعر الصرف</div>
        <div class="card-body">
            <form action="{{ route('admin-currency-exchange') }}" class="form-admin" method="post">
                {{ csrf_field() }}
                <div class="form-row exchange-rate-main">
                    <div class="col-md-3 mb-3">
                        <label for="from-currency" class="lbl-currency col-md-12"> من (العملة الرئيسية) </label>
                        <select class="form-control" id="from-currency"  name="from-currency" disabled> 
                            <option value="{{ $system_currency->currency_id }}">{{ $system_currency->name }}</option>      
                        </select>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-3">
                        <label for="to_currency"  class="lbl-currency col-md-12"> الى </label>
                        <select class="form-control" id="to-currency"  name="to_currency" data-validation="required"> 
                            <option value="NOT_SELECTED">إختر عملة</option>
                        @foreach ($currencies as $currency) 
                            <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                        @endforeach  
                        </select>
                    </div>
                </div>            
                <div hidden class="col-md-11 col-md-push-1" id="exhange-rate-message" >لا يوجد سعر صرف الرجاء اضافة سعر صرف لهذه العملة</div>
                <div class="form-row exchange-rate-main" id="exchange-rate-input">
                    <label for="exchange_rate" class="col-md-0 col-form-label text-md-right"> سعر الصرف </label>
                    <div class="col-md-3">
                        <input type="number" id="exchange-rate" class="form-control no-arrows" name="exchange_rate" disabled min="0.01" step="any" required>
                    </div>
                </div>            
                <div class="form-row exchange-rate-main" id="btn-exchange-rate">
                    <div class="form-group col-md-3 col-sm-3 col-xs-12 right">
                        <button type="submit" class="btn btn-success btn-block" id="save" disabled>حفظ</button>
                    </div>
                    <div class="form-group col-md-3 col-sm-3 col-xs-12 right">
                        <button type="button" class="btn btn-secondary btn-block" onclick="history.back()">الغاء</button>
                    </div>                
                </div>
            </form>
        </div>
    </div>        
@stop

@section('scripts')
<script src="{{ URL::asset('js/admin/currency.js') }}"></script>
<script>
    @if (session('success') == 'true')
        SuccessAlert("{{ session('message') }}");
        scrollPageTop();
    @elseif(session('success') == 'false')
        ErrorAlert("{{ session('message') }}");
        scrollPageTop();
    @endif
</script>
@stop