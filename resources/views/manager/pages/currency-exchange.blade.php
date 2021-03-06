@extends('layouts.manager')

@section('pageTitle', 'اضافة عملات')

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('css/manager/currency.css') }}">
@stop

@section('content')
    <span id="getExchangeRate" hidden>{{ route('currency-exchange-get', '') }}</span>
    <h1 class="h3 mb-4 text-gray-800 main-title"> تحويل العملات </h1>
    <div class="row">
        <div class="col-lg-12 main-panel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">تحويل العملات</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('currency-exchange') }}" class="form-admin" id="" method="post">
                        {{ csrf_field() }}
                        <div class="form-row exchange-rate-main">
                            <div class="col-md-3 mb-3">
                                <label for="from-currency" class="lbl-currency col-md-12"> من (العملة الرئيسية) </label>
                                <select class="form-control" id="from-currency" name="from_currency" data-validation="required" disabled> 
                                    <option value="{{ $organization_currency->currency_id }}">{{ $organization_currency->name }}</option>      
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
                                <input type="number" name="exchange_rate" id="exchange-rate" class="form-control no-arrows"disabled min="0.01" step="any" required>
                            </div>
                        </div>
                        <div class="form-row exchange-rate-main" id="btn-exchange-rate">
                            <div class="form-group col-md-3 col-sm-3 col-xs-12 right">
                                <button type="submit" id="save" class="btn btn-success btn-block" disabled>حفظ</button>
                            </div>
                            <div class="form-group col-md-3 col-sm-3 col-xs-12 right">
                                <button type="button" class="btn btn-secondary btn-block" onclick="history.back()">الغاء</button>
                            </div>                
                        </div>            
                    </form>                
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script src="{{ URL::asset('js/manager/currency.js') }}"></script>
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