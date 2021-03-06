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

	<form action="{{ route('receipt-add') }}" class="form-admin" id="receipt_submit" method="post">
     {{ csrf_field() }}
	 <input type="hidden" name="allcheques" id="allcheques">

		<div class="col-12 text-center receipt-header">{{ auth()->user()->manager()->organization()->name }}</div>
		<div class="col-12 text-center receipt-number">{{$invoice_num ?? 'R00001'}}</div>
		
		<div class = "row" style="direction: rtl;">
			<div class="col-12 col-md-6">
				<div class="col-12 text-center colorBlackBold row" style="padding: 3px;border: 1px solid #aaa;margin:0px;height: 40px;">
				<div class="col-6">
				وصلني من السيد: 
				</div>
					<div class="form-group col-6">
						<select dir="rtl" class="form-control js-example-basic-single" id="customer-name" name="customer" data-validation="required" required> 
							<option dir="rtl" class="text-right" value="" >إختر اسم</option>


							@foreach( $customers as $customer)
							<option dir="rtl" class="text-right" value="C.{{ $customer->id }}">
							{{ $customer->name }} 
							</option>
							@endforeach 

							@foreach( $suppliers as $supplier)
							<option dir="rtl" class="text-right" value="S.{{ $supplier->id }}">
							{{ $supplier->name }} 
							</option>
							@endforeach 

							@foreach( $employees as $employee)
							<option dir="rtl" class="text-right" value="E.{{ $employee->id }}">
							{{ $employee->name }} 
							</option>
							@endforeach 
						</select>
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
					  <td id="cashvalue_basecurrency">
						<input type="text" id="cash_basecurrency" name="cash_basecurrency" class="inputwithoutborder text-center">
					  </td>
					  <td id="cashexchange_basecurrency" class="readonlycell">
						1
					  </td>
					  <td id="cashafterexchange_basecurrency" class="readonlycell"></td>
					</tr>
				  @foreach($currencies as $currency)
					<tr class="colorBlack">
					  <td id="cashcurrency_{{ $currency->id }}" class="readonlycell">{{ $currency->name }}</td>
					  <td id="cashvalue_{{ $currency->id }}">
						<input type="text" id="cash_{{ $currency->id }}" name="cash_{{ $currency->id }}" class="inputwithoutborder text-center">
					  </td>
					  <td id="cashexchange_{{ $currency->id }}">
						<input type="text" id="cashexchangeinput_{{ $currency->id }}" value="{{ $currency->value }}" name="cashexchangeinput_{{ $currency->id }}" class="inputwithoutborder text-center">							
					  </td>
					  <td id="cashafterexchange_{{ $currency->id }}" class="readonlycell"></td>
					</tr>
				  @endforeach																					
				  </tbody>
				</table>
			</div>
			</div>
			<div class="col-12 col-md-1" style="height: 0px;"></div>
			<div class="col-12 col-md-5">
				<div class="col-12 text-center colorBlackBold row" style="padding: 3px;border: 1px solid #aaa;margin: 0px;height: 40px;">
				<div class="col-6">
					التاريخ:
				</div>
				<div class="col-6">
					<div class="mx-auto form-group">
							<input type="text" class="form-control" id="datepicker_main" name="datepicker_main" required readonly style="background:white;">
					</div>
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
					  <td id="cash_total" class="readonlycell">0</td>
					  <td id="cheque_total" class="readonlycell">0</td>
					  <td id="all_total" class="readonlycell">0</td>
					</tr>					
				  </tbody>
				</table>
				</div>

				<div class="col-12 text-center colorBlack" style="padding-bottom: 5px;">ملاحظات</div>
				<div class="col-12 text-center" style="border: 1px solid #aaa;height:40px;">
					<input type="text" id="notes" name="notes" class="inputwithoutborder text-center col-12" style="height: 40px; max-width: 100%; padding: 0px; margin: 0px;">
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
				</div>
			</div>

			</div>
		</div>
		
		<br>
		
		<div class="form-row exchange-rate-main" id="btn-exchange-rate">
			<div class="form-group col-md-3 col-sm-3 col-xs-12 right">
				<button type="button" id="save" class="btn btn-success btn-block" onclick="submitFunction()">حفظ</button>
			</div>
			<div class="form-group col-md-3 col-sm-3 col-xs-12 right">
				<button type="button" class="btn btn-secondary btn-block" onclick="history.back()">الغاء</button>
			</div>                
		</div> 

</form>	
    </div>


@stop
@section('scripts')
	<script src="{{ URL::asset('js/jquery.js') }}"></script>
	<script src="{{ URL::asset('js/jquery-ui.js') }}"></script>
    <script src="{{ URL::asset('js/manager/moment.js') }}"></script>
	<script src="{{ URL::asset('js/manager/jquery-bootstrap-purr.min.js') }}"></script>
	<script src="{{ URL::asset('js/datepicker-ar.js') }}"></script>
	

	 <script>
	  $(document).on('focusin', '.datepicker_', function(){
		  $(this).datepicker({ dateFormat: 'yy-mm-dd' });
		});
	
  $( function() {
	$("#datepicker_main").datepicker({ dateFormat: 'yy-mm-dd' }).datepicker("setDate", new Date());
  } );
</script>

	@foreach($currencies as $currency)
			<script>
		$("#cash_{{$currency->id}}").keyup(function() {
    var val = $("#cash_{{$currency->id}}").val();
    if (parseFloat(val) < 0 || isNaN(val)) {
        window.setTimeout(function setTimeout() {
            $.bootstrapPurr('يجب كتابة رقم', {
            type: 'danger',
            delayPause: true,
            width: 'auto',                    
            allowDismiss: true,
            allowDismissType: 'hover'
            });
            }, 1);
        $("#cash_{{$currency->id}}").val("");
        $("#cash_{{$currency->id}}").focus();   
    }
});
	</script>		
	@endforeach
				  
	
	<script>
		$("#cash_basecurrency").keyup(function() {
    var val = $("#cash_basecurrency").val();
    if (parseFloat(val) < 0 || isNaN(val)) {
        window.setTimeout(function setTimeout() {
            $.bootstrapPurr('يجب كتابة رقم', {
            type: 'danger',
            delayPause: true,
            width: 'auto',                    
            allowDismiss: true,
            allowDismissType: 'hover'
            });
            }, 1);
        $("#cash_basecurrency").val("");
        $("#cash_basecurrency").focus();   
    }
});
	</script>


	
	<script>
			$(document).ready(function() {
								
				$("#cash_basecurrency").on("keydown keyup", function() {
					cash_basecurrency_multiple();
				});									
			});
	</script>
	
		@foreach($currencies as $currency)
			<script>
				$("#cash_{{$currency->id}}, #cashexchangeinput_{{$currency->id}}").on("keydown keyup", function() {
				var num1 = document.getElementById('cash_{{$currency->id}}').value;
				var num2 = document.getElementById('cashexchangeinput_{{$currency->id}}').value;

				var result = parseFloat(num1) * parseFloat(num2);
				if (!isNaN(result)) {
					document.getElementById('cashafterexchange_{{$currency->id}}').innerText = Math.round(result * 100) / 100;
					}else{
					document.getElementById('cashafterexchange_{{$currency->id}}').innerText = '0';
				}
				
				total_cash();
				});	
			</script>
		@endforeach
		
	
	<script>
		function cash_basecurrency_multiple() {
			var num1 = document.getElementById('cash_basecurrency').value;
			var num2 = document.getElementById('cashexchange_basecurrency').innerText;

			var result = parseFloat(num1) * parseFloat(num2);
			if (!isNaN(result)) {
				document.getElementById('cashafterexchange_basecurrency').innerText = Math.round(result * 100) / 100;
				}else{
				document.getElementById('cashafterexchange_basecurrency').innerText = '0';
			}
			
			total_cash();
		}

		function total_cash() {
			var totalcash = 0.0;
			var array =  {!! json_encode($currencies) !!};
			
			var value_basecurrency = document.getElementById('cashafterexchange_basecurrency').innerText;
			if (!isNaN(parseFloat(value_basecurrency))) {
				totalcash += parseFloat(value_basecurrency);
			}
			for(i = 0; i < array.length; i++) {
				var value = document.getElementById('cashafterexchange_'+array[i]['id']).innerText;
				if (!isNaN(parseFloat(value))) {
					totalcash += parseFloat(value);
				}
			}
			

			
			document.getElementById('cash_total').innerText =  Math.round(totalcash * 100) / 100;
			
			all_total();
		}
		
		function all_total() {
			var alltotal = 0.0;
			var num1 = document.getElementById('cash_total').innerText;
			var num2 = document.getElementById('cheque_total').innerText;		
			
			if (!isNaN(parseFloat(num1))) {
				alltotal += parseFloat(num1);
			}
						
			if (!isNaN(parseFloat(num2))) {
				alltotal += parseFloat(num2);
			}		
			
			document.getElementById('all_total').innerText =  Math.round(alltotal * 100) / 100;

		}
	</script>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	@if(Session::has('addReceipt'))
<script type="text/javascript">
window.setTimeout(function setTimeout() {
                    $.bootstrapPurr('{{Session::get("addReceipt")}}', {
                    type: 'success',
                    delayPause: true,
                    width: 'auto',                    
                    allowDismiss: true,
                    allowDismissType: 'hover'
                });
            }, 1);
</script>
@endif
	
	
		@if(Session::has('errorReceipt'))
<script type="text/javascript">
window.setTimeout(function setTimeout() {
                    $.bootstrapPurr('{{Session::get("errorReceipt")}}', {
                    type: 'danger',
                    delayPause: true,
                    width: 'auto',                    
                    allowDismiss: true,
                    allowDismissType: 'hover'
                });
            }, 1);
</script>
@endif
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
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
			
			var th = document.createElement('th');
            th.innerHTML = 'الأوامر';
			th.setAttribute('colspan','2');
			
			tr.appendChild(th);


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

                // *** CANCEL OPTION.
                tr.appendChild(this.td);
                var lblCancel = document.createElement('input');
                lblCancel.innerHTML = '✖';
                lblCancel.setAttribute('onclick', 'crudApp.Cancel(this)');
                lblCancel.setAttribute('style', 'display:none;');
                lblCancel.setAttribute('value', 'حذف');
                lblCancel.setAttribute('id', 'lbl' + i);
                this.td.appendChild(lblCancel);

                // *** SAVE.
                tr.appendChild(this.td);
                var btSave = document.createElement('input');

                btSave.setAttribute('type', 'button');      // SET ATTRIBUTES.
                btSave.setAttribute('value', 'حفظ');
				btSave.setAttribute('class', 'btn btn-primary');
                btSave.setAttribute('id', 'Save' + i);
                btSave.setAttribute('style', 'display:none;');
                btSave.setAttribute('onclick', 'crudApp.Save(this)');       // ADD THE BUTTON's 'onclick' EVENT.

				this.td.appendChild(btSave);

                // *** UPDATE.
                tr.appendChild(this.td);
                var btUpdate = document.createElement('input');

                btUpdate.setAttribute('type', 'button');    // SET ATTRIBUTES.
                btUpdate.setAttribute('value', 'تعديل');
                btUpdate.setAttribute('id', 'Edit' + i);
				btUpdate.setAttribute('class', 'btn btn-secondary');
                btUpdate.setAttribute('style', 'background-color:#44CCEB;');
                btUpdate.setAttribute('onclick', 'crudApp.Update(this)');   // ADD THE BUTTON's 'onclick' EVENT.
                this.td.appendChild(btUpdate);

                // *** DELETE.
                this.td = document.createElement('th');
                tr.appendChild(this.td);
                var btDelete = document.createElement('input');
                btDelete.setAttribute('type', 'button');    // SET INPUT ATTRIBUTE.
                btDelete.setAttribute('value', 'حذف');
				btDelete.setAttribute('class', 'btn btn-danger');
                btDelete.setAttribute('style', 'background-color:#ED5650;');
                btDelete.setAttribute('onclick', 'crudApp.Delete(this)');   // ADD THE BUTTON's 'onclick' EVENT.
                this.td.appendChild(btDelete);
				

            }


            // ADD A ROW AT THE END WITH BLANK TEXTBOXES AND A DROPDOWN LIST (FOR NEW ENTRY).

            tr = table.insertRow(-1);           // CREATE THE LAST ROW.


            for (var j = 0; j < this.col.length; j++) {
                var newCell = tr.insertCell(-1);
                newCell.setAttribute('id', i);
                
				if(j == 0) {
						var tBox = document.createElement('input');          // CREATE AND ADD A TEXTBOX.
                        tBox.setAttribute('type', 'text');
                        tBox.setAttribute('value', '');
						tBox.setAttribute('readonly', 'true');
						tBox.setAttribute('class', 'inputwithoutborder readonlycell text-center');
                        newCell.appendChild(tBox);
						newCell.setAttribute('class', 'readonlycell');
					} else if (j >= 1) {				
					
					if(j == 5) {
						var tBox = document.createElement('input'); 
                        tBox.setAttribute('type', 'text');
                        tBox.setAttribute('value', '');
						tBox.setAttribute('id', 'datepicker_' + i);
						tBox.setAttribute('class', 'inputwithoutborder text-center datepicker_');
						tBox.setAttribute('onblur', 'checkdate('+i+')');
						
                        newCell.appendChild(tBox);							
					}
					else if(j == 6) {
                    	var tBox = document.createElement('input');          
                        tBox.setAttribute('type', 'text');
                        tBox.setAttribute('value', '');
                        tBox.setAttribute('id', 'chequevalue' + i);
                        tBox.setAttribute('onkeyup', 'chequevalue('+i+')');
                       // tBox.setAttribute('onkeydown', 'chequevalue('+i+')');
						tBox.setAttribute('class', 'inputwithoutborder text-center');
                        newCell.appendChild(tBox);
                    }

                    else if (j == 7) {   // WE'LL ADD A DROPDOWN LIST AT THE SECOND COLUMN (FOR currencies).

                        var select = document.createElement('select');      // CREATE AND ADD A DROPDOWN LIST.
                        select.setAttribute('id', 'selectcurrency' + i);                        
						select.setAttribute('onchange','selectcurrencychange('+i+')');
						select.setAttribute('style','width:80px');
						select.setAttribute('class','form-control');
												
                        //select.innerHTML = '<option value=""></option>';
                        for (k = 0; k < this.currencies.length + 1; k++) {
							if(k == 0) {
								select.innerHTML = select.innerHTML + 
                                '<option value="basic" selected>' + this.basecurrency['name'] + '</option>';
							} else {
								select.innerHTML = select.innerHTML +
                                '<option value="' + this.currencies[k - 1]['id'] + '">' + this.currencies[k - 1]['name'] + '</option>';
							}
                        }
                        newCell.appendChild(select);
                    }else if(j == 8) {
                    	var tBox = document.createElement('input');          
                        tBox.setAttribute('type', 'text');
                        tBox.setAttribute('value', '');
                        tBox.setAttribute('readonly','true');
                        tBox.setAttribute('id', 'exchange' + i);
						tBox.setAttribute('onkeyup', 'exchange('+i+')');
                        tBox.setAttribute('onkeydown', 'exchange('+i+')');
                        tBox.setAttribute('class', 'inputwithoutborder text-center');

						newCell.setAttribute('id', 'tdexchange' + i);
						newCell.setAttribute('class', 'readonlycell');
                        newCell.appendChild(tBox);
						

                    }
                    else if(j == 9) {
                    	var tBox = document.createElement('input');          
                        tBox.setAttribute('type', 'text');
                        tBox.setAttribute('readonly','true');
                        tBox.setAttribute('class','readonlycell');
                        tBox.setAttribute('value', '');
                        tBox.setAttribute('id', 'totalafterexchange' + i);
						tBox.setAttribute('class', 'inputwithoutborder text-center');
						
						newCell.setAttribute('class', 'totalafterexchange');
                        newCell.appendChild(tBox);
                    }
                    else {
                        var tBox = document.createElement('input');          // CREATE AND ADD A TEXTBOX.
                        tBox.setAttribute('type', 'text');
                        tBox.setAttribute('value', '');
						tBox.setAttribute('class', 'inputwithoutborder text-center');
                        newCell.appendChild(tBox);
                    }
                }
            }

            this.td = document.createElement('td');
            tr.appendChild(this.td);

            var btNew = document.createElement('input');

            btNew.setAttribute('type', 'button');       // SET ATTRIBUTES.
            btNew.setAttribute('value', '+');
			btNew.setAttribute('class', 'btn btn-primary');
            btNew.setAttribute('id', 'New' + i);
            btNew.setAttribute('style', 'background-color:#207DD1;');
            btNew.setAttribute('onclick', 'crudApp.CreateNew(this)');       // ADD THE BUTTON's 'onclick' EVENT.
            
			this.td.setAttribute('colspan', '2');

			this.td.appendChild(btNew);

            var div = document.getElementById('container');
            div.innerHTML = '';
            div.appendChild(table);    // ADD THE TABLE TO THE WEB PAGE.
			
			selectcurrencychange(i);

        };

        // ****** OPERATIONS START.

        // CANCEL.
        this.Cancel = function (oButton) {

            // HIDE THIS BUTTON.
            oButton.setAttribute('style', 'display:none; float:none;');

            var activeRow = oButton.parentNode.parentNode.rowIndex;

            // HIDE THE SAVE BUTTON.
            var btSave = document.getElementById('Save' + (activeRow - 1));
            btSave.setAttribute('style', 'display:none;');

            // SHOW THE UPDATE BUTTON AGAIN.
            var btUpdate = document.getElementById('Edit' + (activeRow - 1));
            btUpdate.setAttribute('style', 'display:block; margin:0 auto; background-color:#44CCEB;');

            var tab = document.getElementById('chequesTable').rows[activeRow];

            for (i = 0; i < this.col.length; i++) {
                var td = tab.getElementsByTagName("td")[i];
                td.innerHTML = this.cheques[(activeRow - 1)][this.col[i]];
            }
        }


        // EDIT DATA.
        this.Update = function (oButton) {
            var activeRow = oButton.parentNode.parentNode.rowIndex;
            var tab = document.getElementById('chequesTable').rows[activeRow];

            var id = tab.getElementsByTagName("td")[0].innerText;
            id = parseInt(id) - 1;
			var cur = tab.getElementsByTagName("td")[7].innerText;


            // SHOW A DROPDOWN LIST WITH A LIST OF CATEGORIES.
            for (i = 1; i < this.col.length; i++) {
				if(i == 5) {
					var td = tab.getElementsByTagName("td")[i];
                    var ele = document.createElement('input');      // TEXTBOX.
                    ele.setAttribute('type', 'text');
                    ele.setAttribute('value', td.innerText);
                    td.innerText = '';
					ele.setAttribute('id','datepicker_' + id);
					
					ele.setAttribute('class', 'inputwithoutborder text-center datepicker_');
					ele.setAttribute('onblur', 'checkdate('+i+')');

                    td.setAttribute('id', id);
                   td.appendChild(ele);
				   
				} 
				else if(i == 6) {
                    var td = tab.getElementsByTagName("td")[i];
                    var ele = document.createElement('input');      // TEXTBOX.
                    ele.setAttribute('type', 'text');
                    ele.setAttribute('value', td.innerText);
                    
                    ele.setAttribute('id', 'chequevalue' + id);
                    ele.setAttribute('onkeyup', 'chequevalue('+id+')');
                   // ele.setAttribute('onkeydown', 'chequevalue('+id+')');
					ele.setAttribute('class', 'inputwithoutborder text-center');
                        
                    td.innerText = '';
                    td.setAttribute('id', id);
                    td.appendChild(ele);
                }
                else if (i == 7) {
                    var td = tab.getElementsByTagName("td")[i];
                    var ele = document.createElement('select');      // DROPDOWN LIST.
                    ele.setAttribute('id', 'selectcurrency' + id);  
					ele.setAttribute('style','width:80px');
					ele.setAttribute('class','form-control');
					ele.setAttribute('onchange','selectcurrencychange('+id+')');

                    for (k = 0; k < this.currencies.length + 1; k++) {
						if(k == 0) {						
							if(td.innerText == this.basecurrency['name'] ) {
								ele.innerHTML = ele.innerHTML + '<option value="basic" selected>' + this.basecurrency['name'] + '</option>';

							} else {
								ele.innerHTML = ele.innerHTML + '<option value="basic">' + this.basecurrency['name'] + '</option>';
							}
						} else {
							if(td.innerText == this.currencies[k - 1]['name'] ) {
								ele.innerHTML = ele.innerHTML + '<option value="' + this.currencies[k - 1]['id'] + '" selected>' + this.currencies[k - 1]['name'] + '</option>';

							} else {
								ele.innerHTML = ele.innerHTML + '<option value="' + this.currencies[k - 1]['id'] + '">' + this.currencies[k - 1]['name'] + '</option>';
							}

						}
                    }
                    td.innerText = '';
                    td.setAttribute('id', id);
                    td.appendChild(ele);
                }
                else if(i == 8){
                    var td = tab.getElementsByTagName("td")[i];
                    var ele = document.createElement('input');      // TEXTBOX.
                    ele.setAttribute('type', 'text');
                    ele.setAttribute('value', td.innerText);

					if(cur == this.basecurrency['name']) {
						ele.setAttribute('readonly','true');
					}
					
                    ele.setAttribute('id', 'exchange' + id);
					ele.setAttribute('onkeyup', 'exchange('+id+')');
                    ele.setAttribute('onkeydown', 'exchange('+id+')');
                    td.innerText = '';
					
					ele.setAttribute('class', 'inputwithoutborder text-center');
					td.setAttribute('id', 'tdexchange' + id);
					
					if(cur == this.basecurrency['name']) {
						td.setAttribute('class','readonlycell');
					}
                    td.appendChild(ele);
                }
                else if(i == 9){
                    var td = tab.getElementsByTagName("td")[i];
                    var ele = document.createElement('input');      // TEXTBOX.
                    ele.setAttribute('type', 'text');
                    ele.setAttribute('value', td.innerText);
                    ele.setAttribute('readonly','true');
                    ele.setAttribute('class','readonlycell');
                    ele.setAttribute('id', 'totalafterexchange' + id);
                    td.innerText = '';
					
					ele.setAttribute('class', 'inputwithoutborder text-center');
					
					td.setAttribute('class', 'totalafterexchange');
                    td.setAttribute('id', id);
                    td.appendChild(ele);
                }
                else {
                    var td = tab.getElementsByTagName("td")[i];
                    var ele = document.createElement('input');      // TEXTBOX.
                    ele.setAttribute('type', 'text');
                    ele.setAttribute('value', td.innerText);
                    td.innerText = '';
					
					ele.setAttribute('class', 'inputwithoutborder text-center');
                     td.setAttribute('id', id);
                   td.appendChild(ele);
                }
            }

            var lblCancel = document.getElementById('lbl' + (activeRow - 1));
            lblCancel.setAttribute('style', 'cursor:pointer; display:block; width:20px; float:left; position: absolute;');

            var btSave = document.getElementById('Save' + (activeRow - 1));
            btSave.setAttribute('style', 'display:block; margin-left:30px; float:left; background-color:#2DBF64;');

            // HIDE THIS BUTTON.
            oButton.setAttribute('style', 'display:none;');
        };


        // DELETE DATA.
        this.Delete = function (oButton) {
            var activeRow = oButton.parentNode.parentNode.rowIndex;
            this.cheques.splice((activeRow - 1), 1);    // DELETE THE ACTIVE ROW.
            this.createTable();                         // REFRESH THE TABLE.
			
			document.getElementById('allcheques').value = JSON.stringify(this.cheques);

			var table = document.getElementById("chequesTable");
				
				sum = 0;
				
				for(var i = 1; i < table.rows.length - 1; i++){
					var element = table.rows[i].cells[9].querySelector('input');

					if (element != null){
						if(!isNaN(table.rows[i].cells[9].querySelector('input').value)) {
							sum += parseFloat(table.rows[i].cells[9].querySelector('input').value);
						}
					} else {
						if(!isNaN(table.rows[i].cells[9].innerHTML)) {
							sum += parseFloat(table.rows[i].cells[9].innerHTML);
						}
					}
				}
				

			document.getElementById('cheque_total').innerText = Math.round(sum * 100) / 100;
			document.getElementById('cheque2_total').innerText = Math.round(sum * 100) / 100;	
			
			all_total();
        };

        // SAVE DATA.
        this.Save = function (oButton) {
            var activeRow = oButton.parentNode.parentNode.rowIndex;
            var tab = document.getElementById('chequesTable').rows[activeRow];

            // UPDATE cheques ARRAY WITH VALUES.
            for (i = 1; i < this.col.length; i++) {
                var td = tab.getElementsByTagName("td")[i];
				
				if (td.childNodes[0].getAttribute('type') == 'text' || td.childNodes[0].tagName == 'SELECT') {      

                    var txtVal = td.childNodes[0].value;
                    var index;
                    if(i == 7) {
                    	for(c = 0; c < this.currencies.length + 1; c++) {
							if(c == 0) {
								if(txtVal == "basic") {
									this.cheques[(activeRow - 1)][this.col[i]] = this.basecurrency['name'];
									break;
								}
							} else {
								if(txtVal == this.currencies[c - 1]['id']) {
									index = c - 1;
									this.cheques[(activeRow - 1)][this.col[i]] = this.currencies[index]['name'];
									break;
								}
							}
                        }
                    }
                    
                    else if(i == this.col.length - 1) {
                        this.cheques[(activeRow - 1)][this.col[i]] = txtVal;
                    }
                    else if (txtVal != '') {
                        this.cheques[(activeRow - 1)][this.col[i]] = txtVal.trim();
                    }
                    else {
                        obj = '';
						window.setTimeout(function setTimeout() {
							$.bootstrapPurr('يجب تعبئة جميع الحقول الاجبارية', {
							type: 'danger',
							delayPause: true,
							width: 'auto',                    
							allowDismiss: true,
							allowDismissType: 'hover'
							});
							}, 1);
                        break;
                    }
                }
				
            }
            this.createTable();     // REFRESH THE TABLE.
			
			document.getElementById('allcheques').value = JSON.stringify(this.cheques);

			var table = document.getElementById("chequesTable");
				
				sum = 0;
				
				for(var i = 1; i < table.rows.length - 1; i++){
					var element = table.rows[i].cells[9].querySelector('input');

					if (element != null){
						if(!isNaN(table.rows[i].cells[9].querySelector('input').value)) {
							sum += parseFloat(table.rows[i].cells[9].querySelector('input').value);
						}
					} else {
						if(!isNaN(table.rows[i].cells[9].innerHTML)) {
							sum += parseFloat(table.rows[i].cells[9].innerHTML);
						}
					}
				}
				

			document.getElementById('cheque_total').innerText = Math.round(sum * 100) / 100;
			document.getElementById('cheque2_total').innerText = Math.round(sum * 100) / 100;
			all_total();
        }

        // CREATE NEW.
        this.CreateNew = function (oButton) {
            var activeRow = oButton.parentNode.parentNode.rowIndex;
            var tab = document.getElementById('chequesTable').rows[activeRow];
            var obj = {};

            // ADD NEW VALUE TO cheques ARRAY.
            for (i = 1; i < this.col.length; i++) {
                var td = tab.getElementsByTagName("td")[i];
                
                if (td.childNodes[0].getAttribute('type') == 'text' || td.childNodes[0].tagName == 'SELECT') {      
                // CHECK IF ELEMENT IS A TEXTBOX OR SELECT.
                    var txtVal = td.childNodes[0].value;
                    var index;
                    if(i == 7) {
                    	for(c = 0; c < this.currencies.length + 1; c++) {
                    		if(c == 0) {
								if(txtVal == "basic") {
									index = c;
									obj[this.col[i]] = this.basecurrency['name'];
									break;
								}
							} else {
								if(txtVal == this.currencies[c - 1]['id']) {
									index = c - 1;
									obj[this.col[i]] = this.currencies[index]['name'];
									break;
								}
							}
                        }
                    }
                    
                    else if(i == this.col.length - 1) {
                        obj[this.col[i]] = txtVal;
                    }
                    else if (txtVal != '') {
                        obj[this.col[i]] = txtVal.trim();
                    }
                    else {
                        obj = '';
						window.setTimeout(function setTimeout() {
							$.bootstrapPurr('يجب تعبئة جميع الحقول الاجبارية', {
							type: 'danger',
							delayPause: true,
							width: 'auto',                    
							allowDismiss: true,
							allowDismissType: 'hover'
							});
							}, 1);
							break;
                    }
                }
            }
            obj[this.col[0]] = this.cheques.length + 1;     // NEW ID.

            if (Object.keys(obj).length > 0) {      // CHECK IF OBJECT IS NOT EMPTY.
                this.cheques.push(obj);             // PUSH (ADD) DATA TO THE JSON ARRAY.

                this.createTable();                 // REFRESH THE TABLE.
				document.getElementById('allcheques').value = JSON.stringify(this.cheques);
				var table = document.getElementById("chequesTable");
				
				sum = 0;
				
				for(var i = 1; i < table.rows.length - 1; i++){
					var element = table.rows[i].cells[9].querySelector('input');

					if (element != null){
						if(!isNaN(table.rows[i].cells[9].querySelector('input').value)) {
							sum += parseFloat(table.rows[i].cells[9].querySelector('input').value);
						}
					} else {
						if(!isNaN(table.rows[i].cells[9].innerHTML)) {
							sum += parseFloat(table.rows[i].cells[9].innerHTML);
						}
					}
				}
				

			document.getElementById('cheque_total').innerText = Math.round(sum * 100) / 100;
			document.getElementById('cheque2_total').innerText = Math.round(sum * 100) / 100;
			
			all_total();
	
            }
        }

        // ****** OPERATIONS END.
    }

    crudApp.createTable();

function selectcurrencychange(id) {
	this.currencies =  {!! json_encode($currencies) !!};
	this.basecurrency =  {!! json_encode($organization_currency)  !!};
	
  var value = document.getElementById("selectcurrency" + id).value;
  
	if(value == "basic") {
		$("#exchange" + id).prop('readonly', true);
		var element = document.getElementById("tdexchange" + id);
		element.classList.add("readonlycell");
  		$("#exchange" + id).val("1");
	} else {
	  for(i = 0; i < this.currencies.length; i++) {
		  if(value == this.currencies[i]['id']) {
			$("#exchange" + id).prop('readonly', false);
			var element = document.getElementById("tdexchange" + id);
			element.classList.remove("readonlycell");
			$("#exchange" + id).val(this.currencies[i]['value']);
			break;
		  }
	  }
  }

    chequevalue(id);
}

function checkdate(id) {
	var date = document.getElementById("datepicker_" + id).value;
	if(!moment(date, 'YYYY-MM-DD',true).isValid()) {
		document.getElementById("datepicker_" + id).value = "";
	}
}

function chequevalue(id) {
  	var chequevalue = document.getElementById("chequevalue" + id).value;
	if(isNaN(chequevalue)) {
		chequevalue = "0";
		document.getElementById("chequevalue" + id).value = '0';
	}
  	var exchange = $('#exchange' + id).val();
	
	if(isNaN(exchange)) {
		$('#exchange' + id).val('0');
		exchange = "0";
	}
	
	var total = parseFloat(exchange) * parseFloat(chequevalue);
    if(isNaN(total)) {
    	total = 0;
    }
	
	var table = document.getElementById("chequesTable");
				
	sum = 0;
		
	for(var i = 1; i <= table.rows.length - 1; i++){
		var element = null;
		if(i == id + 1) {
			element = total;
		}
		
		if (element != null){
			if(!isNaN(element)) {
				sum += parseFloat(element);
			}
		} else {
			var s = table.rows[i].cells[9].innerHTML;
			if(!isNaN(s)) {
				sum += parseFloat(s);
			}
		}
	}
	
	if(isNaN(sum)) {
		sum = 0;
	}
		
	document.getElementById('cheque_total').innerText = Math.round(sum * 100) / 100;
	document.getElementById('cheque2_total').innerText = Math.round(sum * 100) / 100;
	
	all_total();
	
	$('#totalafterexchange' + id).val(total);
	
}

function exchange(id) {
  	var chequevalue = document.getElementById("chequevalue" + id).value;
  	var exchange = $('#exchange' + id).val();
	
	if(isNaN(chequevalue)) {
		document.getElementById("chequevalue" + id).value = '0';
	}
  	var exchange = $('#exchange' + id).val();
	
	if(isNaN(exchange)) {
		$('#exchange' + id).val('0');
	}
	
	var total = parseFloat(exchange) * parseFloat(chequevalue);
    if(isNaN(total)) {
    	total = 0;
    }
	
	var table = document.getElementById("chequesTable");
				
	sum = 0;
		
	for(var i = 1; i <= table.rows.length - 1; i++){
		var element = null;
		if(i == id + 1) {
			element = total;
		}
		
		if (element != null){
			if(!isNaN(element)) {
				sum += parseFloat(element);
			}
		} else {
			var s = table.rows[i].cells[9].innerHTML;
			if(!isNaN(s)) {
				sum += parseFloat(s);
			}
		}
	}
	
	if(isNaN(sum)) {
		sum = 0;
	}
		
	document.getElementById('cheque_total').innerText = Math.round(sum * 100) / 100;
	document.getElementById('cheque2_total').innerText = Math.round(sum * 100) / 100;
	
	all_total();
	
	$('#totalafterexchange' + id).val(total);

}



	function submitFunction() {
			var all_total = document.getElementById("all_total").innerHTML;
			var datepicker_main = document.getElementById("datepicker_main").value;
			
			var e = document.getElementById("customer-name");
			var customername = e.options[e.selectedIndex].value;

			if(!isNaN(all_total) && parseFloat(all_total) > 0 && datepicker_main.length > 0 && customername.length > 0) {
				
            var tab = document.getElementById('chequesTable').rows[crudApp.cheques.length + 1];

			if(tab) {
			
				var obj = {};

				for (i = 1; i < crudApp.col.length; i++) {
					var td = tab.getElementsByTagName("td")[i];
					
					if (td.childNodes[0].getAttribute('type') == 'text' || td.childNodes[0].tagName == 'SELECT') {      
						var txtVal = td.childNodes[0].value;
						var index;
						if(i == 7) {
							for(c = 0; c < crudApp.currencies.length + 1; c++) {
								if(c == 0) {
									if(txtVal == "basic") {
										index = c;
										obj[crudApp.col[i]] = crudApp.basecurrency['name'];
										break;
									}
								} else {
									if(txtVal == crudApp.currencies[c - 1]['id']) {
										index = c - 1;
										obj[crudApp.col[i]] = crudApp.currencies[index]['name'];
										break;
									}
								}
							}
						}
						
						else if(i == crudApp.col.length - 1) {
							obj[crudApp.col[i]] = txtVal;
						}
						else if (txtVal != '') {
							obj[crudApp.col[i]] = txtVal.trim();
						} else {
							obj = '';
						}
					}
				}
				
				obj[crudApp.col[0]] = crudApp.cheques.length + 1;

				if (Object.keys(obj).length > 0) {      
					crudApp.cheques.push(obj);            
				}
			}
			
			document.getElementById('allcheques').value = JSON.stringify(crudApp.cheques);
				
			document.getElementById("receipt_submit").submit();
			} else if(isNaN(all_total) || parseFloat(all_total) == 0){
			 window.setTimeout(function setTimeout() {
				$.bootstrapPurr('يرجى إضافة قيمة', {
				type: 'danger',
				delayPause: true,
				width: 'auto',                    
				allowDismiss: true,
				allowDismissType: 'hover'
				});
				}, 1);
			}
			else if(datepicker_main.length == 0){
			 window.setTimeout(function setTimeout() {
				$.bootstrapPurr('يرجى اختيار  التاريخ', {
				type: 'danger',
				delayPause: true,
				width: 'auto',                    
				allowDismiss: true,
				allowDismissType: 'hover'
				});
				}, 1);
			}
			else if(customername.length == 0){
			 window.setTimeout(function setTimeout() {
				$.bootstrapPurr('يرجى اختيار الزبون', {
				type: 'danger',
				delayPause: true,
				width: 'auto',                    
				allowDismiss: true,
				allowDismissType: 'hover'
				});
				}, 1);
			}
		}
	
</script>
	
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>


function formatState (state) {
  if (!state.id) {
    return state.text;
  }
  var $state = $(
		
    	'<span> <i style="float: left !important;" >['+state.id.split(".")[0] +'] </i>' + state.text + '  </span>'
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
	
	
@stop