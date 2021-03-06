@extends('layouts.manager')

@section('pageTitle', 'عرض التقارير')

@section("styles")
<link rel="stylesheet" media="screen" href="{{ URL::asset('css/manager/report.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@stop

@section('content')
    <span id="account-report-url" hidden>{{ route('report-account-lookup') }}</span>
    <div class="row">
        <div class="col-lg-12 main-panel ">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">كشف الحساب</h6>
                </div>
                <div class="card-body">
                    <div class="row colorBlackBold" style="direction: rtl;">
                        <div class="col-6 text-center colorBlackBold row" style="padding: 3px;height: 40px;">
					        <div class="col-sm-12 col-xs-12 col-md-2">
                                اسم الزبون
                            </div>
                            <div class="form-group col-md-6 col-sm-12 col-xs-12">
                                <select dir="rtl" class="form-control js-example-basic-single" id="customer-name" name="customer" data-validation="required" required> 
                                    <option value="-1">إختر اسم</option>

                                    @foreach( $customers as $customer)
                                        @if($organization->customer != null && $organization->customer->id == $customer->id)
                                        <option dir="rtl" class="text-right" value="C.{{ $customer->id }}" selected>{{ $customer->name }} </option>
                                        @else
                                        <option dir="rtl" class="text-right" value="C.{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endif
                                        
                                    @endforeach

                                    @foreach( $suppliers as $supplier)
                                         @if($organization->supplier != null && $organization->supplier->id == $supplier->id)
                                        <option dir="rtl" class="text-right" value="S.{{ $supplier->id }}" selected>
                                        {{ $supplier->name }}  </option>
                                        @else
                                        <option dir="rtl" class="text-right" value="S.{{ $supplier->id }}">
                                        {{ $supplier->name }}  </option>
                                        @endif

                                     @endforeach 

                                    @foreach( $employees as $employee)
                                        @if($organization->employee != null && $organization->employee->id == $employee->id)
                                        <option dir="rtl" class="text-right" value="E.{{ $employee->id }}" selected>
                                         {{ $employee->name }} 
                                        </option>
                                        @else
                                        <option dir="rtl" class="text-right" value="E.{{ $employee->id }}">
                                            {{ $employee->name }} 
                                        </option>
                                        @endif 

                                    @endforeach 
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <label> من تاريخ: </label>
                            <label>1-1-2020</label>
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <label> الى تاريخ:</label>  
                            <label>{{ date('m-d-Y') }}</label>   

                        </div>
                    </div>
                    <div class="report-account-invoice">
                        @if($hasCustomer)
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div class="col-12 text-center account-stmnt-header">{{ $organization->name }}</div>
                                    <div class="account-invoice-table">
                                        <div class=" table-responsive">
                                            <table id="tbl-bill" class="table table-responsive-sm" >
                                                <thead class="thead-dark">
                                                        <tr>
                                                            <td colspan="2"><b>الزبون</b></td>
                                                            <td colspan="2" style="font-weight:bold;font-size:17px;">{{ $organization->customer->name }}</td>
                                                            <td><b>الهاتف</b></td>
                                                            <td colspan="2">{{ $organization->customer->phone1 }}</td>
                                                            <td>{{ $organization->customer->address}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>من تاريخ</b></td>
                                                            <td>01-01-2020</td>
                                                            <td><b>الى تاريخ</b></td>
                                                            <td>{{ date('m-d-Y') }}</td>
                                                            <td><b>الحالة</b></td>
                                                            <td colspan="2">غير مستحق</td>
                                                            <td></td>
                                                        </tr>
                                                        <tr class="header">
                                                            <th><b>الرقم</b></th>
                                                            <th><b>التاريخ</b></th>
                                                            <th><b>النوع</b></th>
                                                            <th><b>الملاحظة</b></th>
                                                            <th><b>الحالة</b></th>
                                                            <th><b>مدين مقيم</b></th>
                                                            <th><b>دائن مقيم</b></th>
                                                            <th><b>الرصيد مقيم</b></th>
                                                        </tr>
                                                    </thead>
                                                <tbody>
                                                @foreach( $transactions as $transaction)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $transaction['date']->format('m-d-Y') }}</td>
                                                        <td>{{ $transaction['title'] }}</td>
                                                        <td>{{ $transaction['note'] }}</td>
                                                        <td>{{ $transaction['status'] }}</td>
                                                        <td>{{ $transaction['debit'] }}</td>
                                                        <td>{{ $transaction['credit'] }}</td>
                                                        <td>{{ $transaction['balance'] }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr class="header">
                                                        <td  colspan="7"><b>الرصيد (بال{{ $organization->organizationCurrency()->currency()->name }})</b></td>
                                                        <td>{{ $account['balance'] }}</td>
                                                    </tr>
                                                </tfoot>
                                            </table>                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
       
    function formatState (state) {
    if (!state.id) {
        return state.text;
    }
    var $state = $(

        '<span> <i style="float: left !important;" >'+ ('['+state.id.split(".")[0]+']').replace("[-1]", "") +' </i> ' + state.text + ' </span>'
    );
    return $state;
    };



    $(document).ready(function() {
    $('.js-example-basic-single').select2(); 
	
	$('.js-example-basic-single').select2({
		templateResult: formatState
	});
});
    </script>
    <script src="{{ URL::asset('js/manager/account-reports.js') }}"></script>
@stop