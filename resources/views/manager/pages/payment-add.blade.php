@extends('layouts.manager')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('css/manager/payments.css') }}">
@stop

@section('pageTitle', 'الدفع')

@section('content')
    <input type="hidden" id="max-payments-fields" value="{{ $max_fields }}" />
    <h1 class="h3 mb-4 text-gray-800 main-title"> الدفع </h1>
    <div class="row">
        <div class="col-lg-12 main-panel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">طريقة الدفع</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <div>
                                    <span class="label-field">اسم الزبون :  </span><span id="customer-name">{{ $customer_name }}</span>
                                </div>
                                <div>
                                    <span class="label-field" >المبلغ المطلوب :  </span>
                                    <span id="customer-name">{{ $reservation->total_cost }}</span>
                                    <span class="currency-field">{{ $reservation->currency()->name }}</span>
                                </div>
                                <div>
                                    <span class="label-field">المبلغ المتبقي :  </span>
                                    <span id="customer-name">{{ $reservation->remaining_amount }}</span>
                                    <span class="currency-field">{{ $reservation->currency()->name }}</span>
                                </div>
                            </div>
                        </div>                
                    </div>
                    <form action="{{ route('payment-add', $reservation->id) }}" class="form-admin" id="payaments-type" method="post">
                        {{ csrf_field() }}
                        <div class="col-md-8">
                            <div id="payments">
                                <div class="row col-md-12 table_direction payment_content" id="payment-form">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label class="lbl">طريقة الدفع</label>
                                                    <div class="form-group">
                                                        <input type="radio" data-type="cash" checked class="pay-type" name="payment[0][pay_type]" value="cash" onclick='switchForm(this)'> نقدي
                                                        <input type="radio" data-type="cheque" class="pay-type" name="payment[0][pay_type]" value="cheque" onclick='switchForm(this)'> شيك
                                                    </div>
                                                </div>
                                            </div>                
                                        </div>
                                        <div class="cash-content">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <label class="lbl"> المبلغ </label>
                                                        <input type="number" name="payment[0][cash_amount]" min="1" class="form-control" data-validation="required">
                                                    </div>
                                                </div>          
                                            </div>
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <label class="lbl"> العملة </label>
                                                        <select class="form-control" name="payment[0][cash_currency]" data-validation="required"> 
                                                        @foreach ($currencies as $currency) 
                                                            <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                                        @endforeach  
                                                        </select>
                                                    </div>
                                                </div>          
                                            </div>
                                        </div>                    
                                        <div class="cheque-content">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <label class="lbl"> رقم حساب البنك </label>
                                                        <input type="text" name="payment[0][bank_account]" class="form-control" data-validation="required">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="lbl"> رقم  الشيك</label>
                                                        <input type="text" name="payment[0][cheque_num]"  class="form-control" data-validation="required">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="lbl">  البنك </label>
                                                        <input type="text" name="payment[0][bank_name]" class="form-control" data-validation="required">
                                                    </div>
                                                    <div class="form-group">
                                                        <label  class="lbl">   الفرع </label>
                                                        <input type="text" name="payment[0][bank_branch]" class="form-control" >
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="lbl"> تاريخ الشيك </label>
                                                        <input type="date" name="payment[0][cheque_date]" class="form-control" data-validation="required"> 
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="lbl"> المبلغ </label>
                                                        <input type="number" name="payment[0][cheque_amount]" min="1" class="form-control" data-validation="required">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="lbl"> العملة </label>
                                                        <select class="form-control" name="payment[0][cheque_currency]" data-validation="required"> 
                                                        @foreach ($currencies as $currency) 
                                                            <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                                        @endforeach  
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="lbl"> الملاحظات </label>
                                                        <textarea name="payment[0][note]" rows="4" class="form-control"></textarea>
                                                    </div>
                                                </div>          
                                            </div>
                                        </div>     
                                    </div>               
                                </div>
                            </div>
                            <!-- -->
                            <div class="row add_btn">
                                <button type="button" class="btn btn-primary btn-icon-split" id="btn-add-payment">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    &nbsp;اضافة دفعة جديدة&nbsp;&nbsp;
                                </button>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-5 col-sm-3 col-xs-12 right">
                                        <button {{ $reservation->payment_status ? "disabled" : "" }} type="submit" class="btn btn-success btn-block" id="save">حفظ</button>
                                </div>
                                <div class="form-group col-md-5 col-sm-3 col-xs-12 right">
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
    <script src="{{ URL::asset('js/manager/payments.js') }}"></script>
    <script>
        validateForm("payaments-type");
        
        @if (session('success') == 'true')
            SuccessAlert("{{ session('message') }}");
            scrollPageTop();
        @elseif(session('success') == 'false')
            ErrorAlert("{{ session('message') }}");
            scrollPageTop();
        @endif
    </script>
@stop