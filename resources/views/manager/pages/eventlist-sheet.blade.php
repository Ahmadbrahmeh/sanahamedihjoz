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
                        <h2 id="titel-text">جدول المناسبات</h2>
                    </div>
                </li>
                <li>
                    <h3 id="titel-text-hall-name">{{ $organization->name }}</h3>
                </li>
                <li>
                    <div id="name">
                        <b>
                            <p>
                                <span>السيد : </span><span>{{ $reservation->customer_name }} </span>
                            </p>
                        </b>
                    </div>
                </li>
                <li>                
                    <div id="date">
                        <b>
                            <p>
                                <span>التاريخ :</span> <span>{{ date('m-d-y') }}</span>
                            </p>
                        </b>
                    </div>
                </li>
                <li id="tbl">
                    <table id="tbl-bill">    
                        @foreach($reservation->eventlists as $eventlist)                       
                        <tr>
                            <td>{{ $eventlist->question }}</td>
                            <td>{{ $eventlist->answer }}</td>
                        </tr>
                        @endforeach
                    </table>                    
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