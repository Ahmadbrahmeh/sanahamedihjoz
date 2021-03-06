@extends('layouts.manager')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('css/manager/receipt.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="{{ URL::asset('css/datepicker-jquery-ui.css') }}">
@stop

@section('pageTitle', 'سند القبض')

@section('content')
    <h1 class="h3 mb-4 text-gray-800 main-title"> سند القبض </h1>
    <div class="container">

		<div class="col-12 text-center receipt-header">{{ auth()->user()->manager()->organization()->name }}</div>
		<div class="col-12 text-center receipt-number">
		{{$receipt->invoice_number}} سند قبض رقم</div>
		
		<div class = "row" style="direction: rtl;">
			<div class="col-12 col-md-6">
				<div class="col-12 text-center colorBlackBold row" style="padding: 3px;border: 1px solid #aaa;margin:0px;height: 40px;">
				<div class="col-12">
				وصلني من السيد: 
				@if ( $receipt-> client_type == 'C')
					{{ $receipt->customer()->name }}
				@elseif ($receipt-> client_type == 'S')
					{{ $receipt->supplier()->name }}
				@else ($receipt-> client_type == 'E')
					{{ $receipt->employee()->name }}
				@endif
				</div>
				</div>
				<div class="col-12 text-center colorBlackBold" style="padding: 5px;border: 1px solid #aaa;">المقبوضات نقداً</div>

			<div class="table-responsive">
				<table class="table table-bordered table-hover text-center">
				  <thead class="thead-dark">
					<tr>
					  <th scope="col">العملة</th>
					  <th scope="col">المبلغ</th>
					  <th scope="col">سعر الصرف</th>
					  <th scope="col">مقيم</th>
					</tr>
				  </thead>
				  <tbody>
				  <tr class="colorBlack">
					  <td id="cashcurrency_basecurrency" class="readonlycell">{{ $organization_currency->name }}</td>
					  
						@if($receipt->receiptsCash()->contains('currency_id', $organization_currency->currency_id))
							@foreach($receipt->receiptsCash() as $receiptCash)
								@if($receiptCash->currency_id == $organization_currency->currency_id)
								<td id="cashvalue_basecurrency">	
								<input type="text" id="cash_basecurrency" name="cash_basecurrency" value="{{$receiptCash->amount}}" class="inputwithoutborder text-center readonlycell" readonly>								
								 </td>
							  <td id="cashexchange_basecurrency" class="readonlycell">
								1
							  </td>
							  <td id="cashafterexchange_basecurrency" class="readonlycell">{{$receiptCash->net_amount}}</td>
							</tr>
								@endif
							@endforeach
						@else
					<td id="cashvalue_basecurrency">
							<input type="text" id="cash_basecurrency" name="cash_basecurrency" class="inputwithoutborder text-center readonlycell" readonly>
						 </td>
					  <td id="cashexchange_basecurrency" class="readonlycell" readonly>
						1
					  </td>
					  <td id="cashafterexchange_basecurrency" class="readonlycell" readonly></td>
					</tr>
						@endif
					 
				  @foreach($currencies as $currency)
				  
				  @if($receipt->receiptsCash()->contains('currency_id', $currency->id))
						@foreach($receipt->receiptsCash() as $receiptCash)
							@if($receiptCash->currency_id == $currency->id)
						<tr class="colorBlack">
					  <td id="cashcurrency_{{ $currency->id }}" class="readonlycell">{{ $currency->name }}</td>
					  <td id="cashvalue_{{ $currency->id }}">
						<input type="text" id="cash_{{ $currency->id }}" value="{{$receiptCash->amount}}" name="cash_{{ $currency->id }}" class="inputwithoutborder text-center readonlycell" readonly>
					  </td>
					  <td id="cashexchange_{{ $currency->id }}">
						<input type="text" id="cashexchangeinput_{{ $currency->id }}" value="{{ $receiptCash->exhange_rate }}" name="cashexchangeinput_{{ $currency->id }}" class="inputwithoutborder text-center readonlycell" readonly>							
					  </td>
					  <td id="cashafterexchange_{{ $currency->id }}" class="readonlycell">{{$receiptCash->net_amount}}</td>
					</tr>
					@endif
						@endforeach
					@else
					<tr class="colorBlack">
					  <td id="cashcurrency_{{ $currency->id }}" class="readonlycell">{{ $currency->name }}</td>
					  <td id="cashvalue_{{ $currency->id }}">
						<input type="text" id="cash_{{ $currency->id }}" name="cash_{{ $currency->id }}" class="inputwithoutborder text-center readonlycell" readonly>
					  </td>
					  <td id="cashexchange_{{ $currency->id }}">
						<input type="text" id="cashexchangeinput_{{ $currency->id }}" value="{{ $currency->value }}" name="cashexchangeinput_{{ $currency->id }}" class="inputwithoutborder text-center readonlycell" readonly>							
					  </td>
					  <td id="cashafterexchange_{{ $currency->id }}" class="readonlycell"></td>
					</tr>
					@endif				  				
					
				  @endforeach																					
				  </tbody>
				</table>
			</div>
			</div>
			<div class="col-12 col-md-1" style="height: 0px;"></div>
			<div class="col-12 col-md-5">
				<div class="col-12 text-center colorBlackBold row" style="padding: 3px;border: 1px solid #aaa;margin: 0px;height: 40px;">
				<div class="col-12">
					التاريخ:
					{{$receipt->receipt_date}}
				</div>
				</div>
				<div class="col-12 text-center colorBlackBold" style="padding: 5px;border: 1px solid #aaa;">مجموع المقبوضات</div>

			<div class="table-responsive">
				<table class="table table-bordered table-hover text-center">
				  <thead class="thead-dark">
					<tr>
					  <th scope="col">نقداً</th>
					  <th scope="col">شيكات</th>
					  <th scope="col">المجموع</th>
					</tr>
				  </thead>
				  <tbody>
					<tr class="colorBlack">
					  <td id="cash_total" class="readonlycell">{{$receipt->total_cash}}</td>
					  <td id="cheque_total" class="readonlycell">{{$receipt->total_cheque}}</td>
					  <td id="all_total" class="readonlycell">{{$receipt->total}}</td>
					</tr>					
				  </tbody>
				</table>
				</div>

				<div class="col-12 text-center colorBlack" style="padding-bottom: 5px;">ملاحظات</div>
				<div class="col-12 text-center" style="border: 1px solid #aaa;height:40px;">
					<input type="text" id="notes" name="notes" value="{{$receipt->notes}}" class="inputwithoutborder text-center col-12" style="height: 40px; max-width: 100%; padding: 0px; margin: 0px;" readonly>
				</div>				
				
			</div>
		</div>
		<br>
		
			<div class = "row" style="direction: rtl;">
			<div class="col-12">
				<div class="col-12 text-center colorBlackBold" style="padding: 5px;border: 1px solid #aaa;">المقبوضات شيكات</div>

			<div id="container" class="table-responsive">
			</div>
			
			<div class="row">
				<div class="col-7 text-center" style="border: 1px solid #ccc;">
					المجموع
				</div>
				<div class="col-1">
				</div>
				<div class="col-4 text-center" id="cheque2_total" style="border: 1px solid #ccc;">
				{{$receipt->total_cheque}}
				</div>
			</div>

			</div>
		</div>

    </div>


@stop
@section('scripts')

	<script src="{{ URL::asset('js/jquery.js') }}"></script>
	<script src="{{ URL::asset('js/jquery-ui.js') }}"></script>
    <script src="{{ URL::asset('js/manager/moment.js') }}"></script>
	<script src="{{ URL::asset('js/manager/jquery-bootstrap-purr.min.js') }}"></script>
	<script src="{{ URL::asset('js/datepicker-ar.js') }}"></script>

	
<script>
    var crudApp = new function () {

        // AN ARRAY OF JSON OBJECTS WITH VALUES.
        this.cheques = []

		this.currencies =  {!! json_encode($currencies) !!};
		this.basecurrency =  {!! json_encode($organization_currency)  !!};
       
        this.col = 
		[
		'#',
		'رقم حساب البنك',
		'رقم الشيك',
		'البنك',
		'الفرع',
		'تاريخ الشيك',
		'مبلغ الشيك',
		'العملة',
		'سعر الصرف',
		'مبلغ الشيك المقيم',
		'ملاحظة'
		];
		
		this.array_cheques = {!! json_encode($receipt->receiptsCheque()) !!};
				

        for (var i = 0; i < this.array_cheques.length; i++) {

			var obj = {};
					
			obj[this.col[0]] = this.cheques.length + 1;
			obj[this.col[1]] = this.array_cheques[i]['bank_account'];
			obj[this.col[2]] = this.array_cheques[i]['cheque_num'];
			obj[this.col[3]] = this.array_cheques[i]['bank_name'];
			obj[this.col[4]] = this.array_cheques[i]['bank_branch'];
			obj[this.col[5]] = this.array_cheques[i]['cheque_date'];
			obj[this.col[6]] = this.array_cheques[i]['amount'];
			
			var currencyId = this.array_cheques[i]['currency_id'];
						
			for(c = 0; c < this.currencies.length + 1; c++) {
				if(c == 0) {
					if(currencyId == this.basecurrency['currency_id']) {
						obj[this.col[7]] = this.basecurrency['name'];
						break;
					}
				} else {
					if(currencyId == this.currencies[c - 1]['id']) {
						obj[this.col[7]] = this.currencies[c - 1]['name'];
						break;
					}
				}
			}						
			obj[this.col[8]] = this.array_cheques[i]['exhange_rate'];
			obj[this.col[9]] = this.array_cheques[i]['net_amount'];
			obj[this.col[10]] = this.array_cheques[i]['note'];
			
			if (Object.keys(obj).length > 0) {
				this.cheques.push(obj); 
			}
		}
		
		
        this.createTable = function () {
					

            // EXTRACT VALUE FOR TABLE HEADER.
            for (var i = 0; i < this.cheques.length; i++) {
                for (var key in this.cheques[i]) {
                    if (this.col.indexOf(key) === -1) {
                        this.col.push(key);
                    }
                }
            }

            // CREATE A TABLE.
            var table = document.createElement('table');
            table.setAttribute('id', 'chequesTable');     // SET TABLE ID.
			table.setAttribute('class','table table-bordered text-center');

            var tr = table.insertRow(-1);               // CREATE A ROW (FOR HEADER).

            for (var h = 0; h < this.col.length; h++) {
                // ADD TABLE HEADER.
                var th = document.createElement('th');

                th.innerHTML = this.col[h].replace('_', ' ');
                tr.appendChild(th);
				tr.setAttribute('class', 'thead-dark');
            }
			


            // ADD ROWS USING JSON DATA.
            for (var i = 0; i < this.cheques.length; i++) {

                tr = table.insertRow(-1);           // CREATE A NEW ROW.

                for (var j = 0; j < this.col.length; j++) {
                    var tabCell = tr.insertCell(-1);
					if(j == 9) {
						tabCell.setAttribute('class', 'totalafterexchange');
					}
                    tabCell.innerHTML = this.cheques[i][this.col[j]];
                }

                // DYNAMICALLY CREATE AND ADD ELEMENTS TO TABLE CELLS WITH EVENTS.

                this.td = document.createElement('td');			
            }


        
            var div = document.getElementById('container');
            div.innerHTML = '';
            div.appendChild(table);    // ADD THE TABLE TO THE WEB PAGE.
			
        };
   
 
    }

    crudApp.createTable();

	
</script>
	
	
@stop