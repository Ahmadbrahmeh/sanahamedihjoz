<!DOCTYPE html>
<html  lang="ar">
    <head>
        <title></title>
        <link rel="stylesheet" media="screen" href="{{ URL::asset('css/manager/main-account-statement.css') }}">
        <link rel="stylesheet" media="print" href="{{ URL::asset('css/manager/print-account-statement.css') }}">
    </head>
    <body>
        <div id="main">
            <div class="invoice-table">
                <div class="table">
                    <table id="tbl-bill" class="center" >
                        <thead>
                            <tr>
                                <td colspan="2"><b>الزبون</b></td>
                                <td colspan="2">{{ $reservation->customer_name }}</td>
                                <td><b>الهاتف</b></td>
                                <td colspan="2">{{ $reservation->customer->phone1 }}</td>
                                <td>{{ $reservation->customer->address }}</td>
                            </tr>
                            <tr>
                                <td>من تاريخ</td>
                                <td>{{ $reservation->created_at->format('m-d-y') }}</td>
                                <td>الى تاريخ</td>
                                <td>{{ date('m-d-y') }}</td>
                                <td>الحالة</td>
                                <td colspan="2">{{ $reservation->status }}</td>
                                <td></td>
                            </tr>
                            <tr class="header">
                                <td><b>الرقم</b></td>
                                <td><b>التاريخ</b></td>
                                <td><b>النوع</b></td>
                                <td><b>الملاحظة</b></td>
                                <td><b>الحالة</b></td>
                                <td><b>مدين مقيم</b></td>
                                <td><b>دائن مقيم</b></td>
                                <td><b>رصيد المقيم</b></td>
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
        <div id="print">
            <div class="form-group">                                    
                <button type="button" class="btn btn-success" id="btn-print"  onclick="window.print()">طباعة</button>
            </div>
        </div>
    </body>
    <script>
        
    </script>
</html>