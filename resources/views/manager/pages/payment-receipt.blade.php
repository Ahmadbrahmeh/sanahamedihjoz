<!DOCTYPE html>
<html lang="ar">
    <head>
        <link rel="stylesheet" media="screen" href="{{ URL::asset('css/manager/payment-receipt.css') }}">   
        <link rel="stylesheet" media="print" href="{{ URL::asset('css/manager/payment-receipt-print.css') }}">
    </head>
    <body>
        <div id="main">
            <div id="right-side">
                <div id="invoice-title">
                    <div id="orgnaiztion-logo" >                        
                        <!-- <img src="logo.png" id="logo"> -->
                    </div>
                    <div id="orgnaiztion-name" >
                        <h2>{{ $organization->name }}</h2>                        
                    </div>
                </div>
                <div id="invoice-tbl">
                    <div id="tbl-right-side">
                        <table id="tbl-customer-info" class="table">
                            <thead>
                                <tr>
                                    <th  colspan="4">وصلني من السيد {{ $reservation->customer_name }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4">المقبوضات نقدا</th>
                                </tr>
                                <tr>
                                    <td>العملة</td>
                                    <td>المبلغ</td>
                                    <td>سعر الصرف</td>
                                    <td>مقيم</td>
                                </tr>
                            </thead>
                            <tbody>
                            @if($payment->paymentsCash()->count() > 0)
                                @foreach($payment->paymentsCash() as $payment_item)
                                <tr>
                                    <td>{{ $payment_item->currency()->name }}</td>
                                    <td>{{ $payment_item->amount }}</td>
                                    <td>{{ $payment_item->exhange_rate }}</td>
                                    <td>{{ $payment_item->net_amount }}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                            @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" id="tbl-customer-info-amount">المجموع (بال{{ $reservation->currency()->name }})</td>
                                    <td>{{ $payment->total_cash }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div id="invoice-number-side">
                        <p>سند قبض</p>
                        <p><span>{{ $payment->invoice_number }} </span> <span>رقم</span></p>
                    </div>
                    <div id="tbl-left-side">
                        <div id="total-paid-side">
                            <table id="table-total-paid" class="table">
                                <tr>
                                    <th>التاريخ</th>
                                    <td colspan="2">{{ date('m-d-y') }}</td>
                                </tr>
                                <tr>
                                    <th colspan="3" id="table-total-paid-amount">مجموع المقبوضات</th>
                                </tr>
                                <tr>
                                    <th>نقدا</th>
                                    <th>شيكات</th>
                                    <th>المجموع (بال{{ $reservation->currency()->name }})</th>
                                </tr>
                                <tr>
                                    <td>{{ $payment->total_cash }}</td>
                                    <td>{{ $payment->total_cheque }}</td>
                                    <td>{{ $payment->total }}</td>
                                </tr>
                            </table>    
                        </div>
                        <div id="note-side">
                            <p>الملاحظات</p>
                            <div id="notes" >
                               
                            </div>        
                        </div>                        
                    </div>
                    <div id="cheque-side">
                        <table id="cheque-table">
                            <thead>
                                <tr>
                                    <th colspan="10">المقبوضات شيكات</th>
                                </tr>    
                                <tr>
                                    <th>رقم حساب البنك</th>
                                    <th>رقم الشيك</th>
                                    <th>البنك</th>
                                    <th>الفرع</th>
                                    <th>تاريخ الشيك</th>
                                    <th>مبلغ الشيك</th>
                                    <th>العمله</th>
                                    <th>سعر الصرف</th>
                                    <th>مبلغ الشيك المقيم</th>
                                    <th>ملاحظات</th>
                                </tr>
                            </thead>
                            <tobdy>
                            @if($payment->paymentsCheque()->count() > 0)
                                @foreach($payment->paymentsCheque() as $payment_item)
                                <tr>
                                    <td>{{ $payment_item->bank_account }}</td>
                                    <td>{{ $payment_item->cheque_num }}</td>
                                    <td>{{ $payment_item->bank_name }}</td>
                                    <td>{{ $payment_item->bank_branch }}</td>
                                    <td>{{ $payment_item->cheque_date }}</td>
                                    <td>{{ $payment_item->amount }}</td>
                                    <td>{{ $payment_item->currency()->name }}</td>
                                    <td>{{ $payment_item->exhange_rate }}</td>
                                    <td>{{ $payment_item->net_amount }}</td>
                                    <td>{{ $payment_item->note }}</td>
                                </tr>
                                @endforeach
                            @else
                            <td colspan="10"> لا يوجد</td>
                            @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="8">المجموع (بال{{ $reservation->currency()->name }})</th>
                                    <td colspan="2">{{ $payment->total_cheque }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        <div class="btn-print-block">
            <button id="btn-print" class="btn btn-success" onclick="window.print()">طباعة</button>
        </div>
    </body>
</html>