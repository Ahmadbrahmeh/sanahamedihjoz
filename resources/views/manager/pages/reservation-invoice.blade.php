<!DOCTYPE html>
<html  lang="ar">
    <head>
        <title></title>
        <link rel="stylesheet" media="screen" href="{{ URL::asset('css/manager/main-bill.css') }}">
        <link rel="stylesheet" media="print" href="{{ URL::asset('css/manager/print-bill.css') }}">
    </head>
    <body>
        <div id="main">
            <ul style="list-style-type: none;">
                <li>
                    <div class="" id="titel">
                        <h2><p id="titel-text">فاتورة الحجز</p></h2>
                    </div>
                </li>
                <li>
                    <div id="name">
                        <p>
                            <b><span>السيد : </span></b>
                            <span>{{  $reservation->customer_name }}</span>
                        </b>
                    </div>
                </li>
                <li>                
                    <div id="date">                  
                        <p>
                            <b><span>التاريخ : </span></b>
                            <span>{{ date('m-d-y') }} </span>
                        </p>
                    </div>
                </li>
                <li id="tbl">
                    <table id="tbl-bill" >
                        <thead>
                            <tr>
                                <th>البيان</th>
                                <th>الحالة</th>
                                <th>ملاحظة</th>
                                <th>المبلغ</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($reservation->statements as $statement)
                            <tr>
                                <td>{{ $statement['title'] }}</td>
                                <td>{{ $statement['status'] }}</td>
                                <td>{{ $statement['note']  }}</td>
                                <td>{{ $statement['cost'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" id="sum"><span> المجموع </span><span>({{ $reservation->currency()->name }})</span></td>
                                <td>{{ $reservation->total_cost }}</td>
                            </tr>
                        </tfoot>
                    </table>                    
                </li>
                <li>
                    <div id="conditions">
                        <p><b>: قائمة الشروط والالتزامات</b></p>
                        <ul id="lst-conditions">
                            @foreach( $reservation->terms()->where("mark_for_delete", false) as $term)
                                <li><span>{{ $term->value }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                </li>
                <li>
                    <div id="note">
                        <p><b>: الملاحظات</b></p>
                        <ul id="lst-note">
                            @foreach( $reservation->notes()->where("mark_for_delete", false) as $note)
                                <li><span>{{ $note->value }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                </li>     
                <li>
                </li>           
            </ul>
        </div>
        <div id="print">
            <div class="form-group">                                    
                <button type="button" class="btn btn-success" id="btn-print"  onclick="window.print()">طباعة</button>
            </div>
        </div>
    </body>
</html>