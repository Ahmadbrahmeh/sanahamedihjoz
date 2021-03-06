@extends('layouts.manager')

@section('pageTitle', 'كشف حساب')

@section('styles')
<link rel="stylesheet" media="screen" href="{{ URL::asset('css/manager/report.css') }}">
@stop

@section('content')

<h1 class="h3 mb-4 text-gray-800 main-title"> كشف حساب </h1>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="col-12 text-center account-stmnt-header">{{$organization->name ?? ''}}</div>
            <div class="account-invoice-table">
                <div class=" table-responsive">
                    <table id="tbl-bill" class="table table-responsive-sm" >
                        <thead class="thead-dark">
                                <tr>
                                    <td colspan="2"><b>الزبون</b></td>
                                    <td colspan="2">{{ $reservation->customer_name }}</td>
                                    <td><b>الهاتف</b></td>
                                    <td colspan="2">{{ $reservation->customer->phone1 }}</td>
                                    <td>{{ $reservation->customer->address }}</td>
                                </tr>
                                <tr>
                                    <td><b>من تاريخ</b></td>
                                    <td>{{ $reservation->created_at->format('m-d-y') }}</td>
                                    <td><b>الى تاريخ</b></td>
                                    <td>{{ date('m-d-y') }}</td>
                                    <td><b>الحالة</b></td>
                                    <td colspan="2">{{ $reservation->status }}</td>
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
                                <td>{{ $transaction['date']->format('m-d-y') }}</td>
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
                                <td  colspan="7"><b>الرصيد (بال{{ $reservation->currency()->name }})</b></td>
                                <td>{{ $reservation->remaining_amount }}</td>
                            </tr>
                        </tfoot>
                    </table>                                        
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
  <script src="{{ URL::asset('js/manager/account-reports.js') }}"></script>
@stop