@extends('layouts.manager')

@section('pageTitle', 'اضافة حجز')


@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('css/manager/reservation.css') }}">
    <link href="{{ URL::asset('css/time-duration.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::asset('css/wickedpicker.css') }}">
    
@stop

@section('content')
@include('manager.modals.add-service-modal')
    <input type="hidden" id="max-hall-fields" value="{{ $reservation->maxLimit['halls'] }}" />
    <input type="hidden" id="max-note-fields" value="{{ $reservation->maxLimit['notes'] }}" />
    <input type="hidden" id="max-term-fields" value="{{ $reservation->maxLimit['terms'] }}" />
    <input type="hidden" id="max-eventlist-fields" value="{{ $reservation->maxLimit['eventlist'] }}" />
    <input type="hidden" id="halls-count" value="0" />
    <input type="hidden" id="notes-count" value="0" />
    <input type="hidden" id="terms-count" value="0" />
    <input type="hidden" id="eventlists-count" value="0" />
    
    <div class="hidden" hidden>
        <select class='form-control'  id='eventlist-questions'>
            @foreach($eventlists as $eventlist)
            <option value='{{ $eventlist->name }}'>{{ $eventlist->name }}</option>
            @endforeach
        </select>
    </div>
    <h1 class="h3 mb-4 text-gray-800 main-title"> الحجوزات</h1>
    <div class="row">
        <div class="col-lg-12 main-panel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">اضافة الحجز</h6>
                </div>			
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
				
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="direction: rtl;">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
          <h4 class="modal-title">اضافة زبون</h4>
        </div>
        <div class="modal-body">
                    <form action="{{ route('customer-add') }}" class="form-admin" id="addCustomerForm" method="post">
                        {{ csrf_field() }}  
						<input type="hidden" name="checker" value="2">
                        <div class="row col-md-12 table_direction">
                            <div class="col-md-12">
                            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="lbl">الاسم</label>
                                <input id="name" type="text" name="name" class="form-control" value="{{ Request::old('name')  }}" data-validation="required">
                                @if ($errors->has('name'))
                                    <span class="help-block form-error">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address" class="lbl">المدينة</label>
                                        <select class="form-control" name="address_city" id="address-city" data-validation="required">
                                            <option value="">اختر مدينة</option>
                                            @foreach($cities as $city)
                                            <option value="{{ $city->code }}">{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group {{ isset($address_types['region']) ? '' : 'hide' }}" id="form-address-region">
                                        <label for="address" class="lbl">المنطقة</label>
                                        <select class="form-control" name="address_region" id="address-region" data-validation="required">
                                            <option value="">اختر منطقة</option>
                                        </select>
                                    </div>
                                </div>  
                                <div class="col-md-4">
                                    <div class="form-group {{ isset($address_types['street']) ? '' : 'hide' }}" id="form-address-street">
                                        <label for="address" class="lbl">الشارع</label>
                                        <select class="form-control" name="address_street" id="address-street">
                                            <option value="">اختر منطقة</option>
                                        </select>
                                    </div>
                                </div>        
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email" class="lbl">البريد الالكتروني</label>
                                        <input id="email" type="text" name="email" class="form-control" value="{{ Request::old('email')  }}" data-validation="email" data-validation-optional="true">
                                        @if ($errors->has('email'))
                                            <span class="help-block form-error">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>  
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('certifcate') ? ' has-error' : '' }}">
                                        <label for="certifcate" class="lbl">رقم الهوية</label>
                                        <input id="certifcate" type="text" name="certifcate" value="{{ Request::old('certifcate')  }}" class="form-control">
                                        @if ($errors->has('certifcate'))
                                            <span class="help-block form-error">{{ $errors->first('certifcate') }}</span>
                                        @endif
                                    </div>
                                </div>           
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('phone1') ? ' has-error' : '' }}">
                                        <label for="phone1" class="lbl">رقم الهاتف الاول</label>
                                        <input id="phone1" type="text" name="phone1" class="form-control" value="{{ Request::old('phone1')  }}" data-validation="required">
                                        @if ($errors->has('phone1'))
                                            <span class="help-block form-error">{{ $errors->first('phone1') }}</span>
                                        @endif
                                    </div>
                                </div>  
                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('phone2') ? ' has-error' : '' }}">
                                        <label for="phone2" class="lbl">رقم الهاتف الثاني</label>
                                        <input id="phone2" type="text" name="phone2" value="{{ Request::old('phone2')  }}" class="form-control">
                                        @if ($errors->has('phone2'))
                                            <span class="help-block form-error">{{ $errors->first('phone2') }}</span>
                                        @endif
                                    </div>
                                </div>  
                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('phone3') ? ' has-error' : '' }}">
                                        <label for="phone3" class="lbl">رقم الهاتف الثالث</label>
                                        <input id="phone3" type="text" name="phone3" value="{{ Request::old('phone3')  }}" class="form-control">
                                        @if ($errors->has('phone3'))
                                            <span class="help-block form-error">{{ $errors->first('phone3') }}</span>
                                        @endif
                                    </div>
                                </div>    
                            </div>
                            <br />
                            <div class="row">
                                <div class="form-group col-md-6 col-sm-3 col-xs-12 right">
                                    <button type="submit" class="btn btn-success btn-block">اضافة</button>
                                </div>
                                <div class="form-group col-md-6 col-sm-3 col-xs-12 right">
                                    <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">الغاء</button>
                                </div>
                            </div>
                        </div>
                    </form>                       
                </div>    

        </div>
      </div>
      
    </div>
  </div>

 
 
                <div class="card-body">
                    <form action="{{ route('reservation-add') }}" class="form-admin" id="reservation-form" method="post">
                        @csrf
                        <div class="row col-md-12 table_direction">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-12" id="btn-add-customer">
										
									  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">اضافة زبون</button>
	
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customer" class="lbl">اسم الحاجز</label>
                                            <select class="form-control js-example-basic-single" id="customer-name" name="customer" data-validation="required"> 
                                                <option value="">إختر اسم</option>
                                                @foreach( $customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title" class="lbl">عنوان المناسبة</label>
                                            <input id="title"  type="text" name="title" class="form-control" data-validation="required">
                                        </div>
                                    </div>          
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="event_date" class="lbl"> تاريخ الحجز </label>
                                            <input id="event-date" readonly type="text"  name="event_date" class="form-control" value="{{ $reservation->event_date }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="preparation-time" class="lbl">وقت التحضير بين المناسبات</label>
                                            <input type="text" id="preparation" data-name-minutes="preparation_minutes" data-name-hours="preparation_hours" data-value-hours="{{ $organization->prepare_duration_hours }}" data-value-minutes="{{ $organization->prepare_duration_minutes }}">
                                        </div>
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="resrvation-time" class="lbl"> وقت الحجز </label>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-3 col-xs-12 right" style="direction: ltr;">
										<input type="text" id="from-time" data-format="HH:mm" data-template="HH : mm" name="start_time" required>
                                        <label for="start_time_localized"" class="lbl"> من </label>									
                                    </div>
                                    <div class="form-group col-md-6 col-sm-3 col-xs-12 right" style="direction: ltr;">
										<input type="text" id="end-time" data-format="HH:mm" data-template="HH : mm" name="end_time" required>										
                                        <label for="end_time_localized"" class="lbl">  الى </label>
									</div>
                                </div>
								   <div class="alert alert-danger messageError" style="display:none; text-align: right;"></div>

                                <div class="row">
                                    <div class="form-group" id="included-form">
                                        <label for="pay_type" class="lbl">نوع الحجز</label>
                                        <div class="form-group">
                                            <input type="radio" data-type="form1" id="pay-type1" class="pay-type" name="pay_type" value="type1" onclick="switchForm(this)" checked="checked"> عادي
                                            <input type="radio" data-type="form2" id="pay-type1" class="pay-type" name="pay_type" value="type2" onclick="switchForm(this)"> حسب عدد الأشخاص 
                                        </div>                                            
                                    </div>
                                      
                                </div>  

                                <label for="resrvation-time-from" class="lbl">القاعات</label>
								
								<div class="row" id="all-hall">
                                        <div class="col-md-12 hall row" style="margin: 5px !important;">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="hall" class="lbl"> القاعة </label>
                                                    <select class="form-control" readonly id="hall-1" name="hall[0][id]" onmousedown="(function(e){ e.preventDefault(); })(event, this)"> 
                                                    @foreach($halls as $hall)
                                                        <option {{ ($reservation->hall->id == $hall->id) ? "selected" : "" }} value="{{ $hall->id }}">{{ $hall->name }}</option>
                                                    @endforeach
                                                    </select>
                                                </div>
												</div>
												<div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="hall" class="lbl"> الملاحظات </label>
                                                    <input type="text" class="form-control" name="hall[0][note]" />
                                                </div>
                                            </div>
											
											<div class="col-md-12 row" id="form1" style="margin: 0px !important; padding: 0px !important;">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="hall[0][price]" class="lbl">السعر (بال{{$reservation->currency->name}})</label>
                                                <input type="number" min="1" id="price" class="form-control" name="hall[0][price]" data-validation="required" value="{{ $specificHall->price }}">                                                                                   
                                            </div>
                                        </div>
										<div class="col-md-6">
                                            <div class="form-group">
                                                <label for="price" class="lbl">عدد الأفراد</label>
                                                <input type="number" min="1" id="capacityview" class="form-control" name="capacityview" value="{{ $specificHall->capacity }}" readonly>                                                                                   
                                            </div>
                                        </div>
                                    </div>
											
											
                                            <div class="col-md-12 form2" style="display: none">
                                                <div class="col-md-12 row" style="margin: 0px !important; padding: 0px !important;">
												<div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="person_number" class="lbl"> عدد الافراد </label>
                                                        <input type="number" min="1" id="person-number" class="form-control" name="hall[0][capacity]" min="1"  value="{{ $reservation->hall->capacity }}" data-validation="required">                                                                                   
                                                    </div>
													</div>
												<div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="hall" class="lbl"> السعر لكل فرد </label>
                                                        <input type="number" min="1" id="person-price" class="form-control" name="hall[0][person_price]" value="{{ $reservation->hall->price }}"  data-validation="required">                                                                                   
                                                    </div>
													</div>
													<div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="hall" class="lbl">المجموع الكلي</label>
                                                        <input type="number" min="1" class="form-control" id="totalprice" value="{{ $reservation->hall->price * $reservation->hall->capacity }}" readonly>                                                                                   
                                                    </div>
													</div>
                                                </div>
                                            </div>                                    
                                        </div>          
                                    </div>
								
                              <!--  <div class="hall-container"> -->
                                    
                                    <div class="form-group">
                                        <!-- <button type="button" class="btn btn-primary" id="btn-add-hall">اضافة قاعة</button> -->
                                    </div>
                              <!--  </div> -->         
                                <div class="row">
                                    <label for="services" class="lbl">الخدمات</label>
                                    <div class="col-md-12" id="services">
                                        <div class="form-group">
                                            <ul id="services-ul">
                                                @foreach($services as $service)
                                                <li>
                                                    <button type="button" data-service-price="{{ $service->price }}" data-service-id="{{ $service->id }}" data-service-selected="false" class="btn btn-service" id="service-btn-{{ $service->id }}" data-toggle="modal" data-target="#service-modal">
                                                        <span style="display:block;">{{ $service->name }}</span>
                                                        <span class="service-price-label" id="service-price-label">{{ $service->price }}</span>
                                                        <span class="service-price-label" >{{ $reservation->currency->name }}</span>
                                                    </button>
                                                    <input type="hidden" id="service-id-{{ $service->id }}" disabled name="service[{{ $loop->iteration - 1 }}][id]" />
                                                    <input type="hidden" id="service-price-{{ $service->id }}" disabled name="service[{{ $loop->iteration - 1}}][price]" />
                                                </li>
                                                @endforeach
                                            </ul>   
                                        </div>
                                    </div>                
                                </div>
								
								<!--<div class="row">
									<table id="myTable">
										<th>
											<td style="display:none;">id</td>
											<td>النوع</td>
											<td>السعر</td>
										</th>
									</table>
								</div> -->
								
                                <div class="row top">
                                    <div class="row">
                                        <label for="price" class="lbl"> الملاحظات </label>
                                    </div>
                                    <div class="col-md-12" id="notes">                                                                          
                                    </div>
                                    <button type="button" class="btn btn-primary btn-icon-split" id="btn-add-note">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-plus"></i>
                                        </span>
                                        &nbsp;اضافة ملاحظات&nbsp;&nbsp;
                                    </button>
                                </div>          
                                <div class="row top">
                                    <label class="lbl"> الشروط والالتزامات </label>
                                    <div class="col-md-12" id="terms">                                                                           
                                    </div>     
                                    <div class="form-group">                                    
                                        <button type="button" class="btn btn-primary btn-icon-split" id="btn-add-term">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-plus"></i>
                                            </span>
                                            &nbsp;اضافة شروط والالتزامات&nbsp;&nbsp;
                                        </button>
                                    </div>      
                                </div>     
                                <div class="row top">
                                    <label for="eventlist" class="lbl">جدول المناسبات</label>                                
                                    <div class="col-md-12" id="all-eventlist">
                                        
                                    </div>
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-icon-split" id="btn-add-eventlist">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-plus"></i>
                                            </span> &nbsp;اضافة جدول مناسبة&nbsp;&nbsp;
                                        </button>
                                    </div>           
                                </div>
                                <div class="row top2">
                                    <div class="form-group col-md-12 col-sm-3 col-xs-12 right">                                        

                                    </div>
                                </div>    
                                <div class="row " >                            
                                    <div class="form-group col-md-5 col-sm-3 col-xs-12 right">
                                        <button type="submit" class="btn btn-success btn-block" id="add">اضافة</button>
                                    </div>
                                    <div class="form-group col-md-5 col-sm-3 col-xs-12 right">
                                        <button type="button" class="btn btn-secondary btn-block" onclick="history.back()">الغاء</button>
                                    </div>
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


 <script src="{{ URL::asset('js/address2.js') }}"></script>
    <script>
        validateForm("addCustomerForm");

        @if (session('success') == 'true')
            SuccessAlert("{{ session('message') }}");
            scrollPageTop();
        @elseif(session('success') == 'false')
            ErrorAlert("{{ session('message') }}");
            scrollPageTop();
        @endif
    </script>
	
    <script src="{{ URL::asset('js/manager/reservation.js') }}"></script>
    <script src="{{ URL::asset('js/time-duration.js') }}"></script>
    <script>
        validateForm("reservation-form");
        $("#preparation").durationPicker();
   
        @if (session('success') == 'true')
            SuccessAlert("{{ session('message') }}");
            scrollPageTop();
        @elseif(session('success') == 'false')
            ErrorAlert("{{ session('message') }}");
            scrollPageTop();
        @endif
    </script>    
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
    $('.js-example-basic-single').select2();
});
</script>


<script>
	$(document).ready(function() {
		
		$("#person-price, #person-number").on("keydown keyup", function() {
			totalprice();
		});
		
		function totalprice() {
            var num1 = document.getElementById('person-number').value;
            var num2 = document.getElementById('person-price').value;
			var result = parseFloat(num1) * parseFloat(num2);
            if (!isNaN(result)) {
                document.getElementById('totalprice').value = Math.round(result * 100) / 100;
                }else{
            	document.getElementById('totalprice').value = '0';
            }
        }
	});
</script>

<script>
function addService() {
  var table = document.getElementById("myTable");
  var count = $('#myTable tr').length;
  var row = table.insertRow(count);
  var cell1 = row.insertCell(0);
  var cell2 = row.insertCell(1);
  var cell3 = row.insertCell(1);
  cell1.innerHTML = "NEW CELL1";
  cell2.innerHTML = "NEW CELL2";
}
</script>
	
	
	<script>
        //  jQuery(document).ready(function(){
		// 	   var fromDate = jQuery('#from-time').val();
		// 	   var toDate = jQuery('#end-time').val();
			   
		// 	   if(fromDate != '' && toDate != '') {
		// 		   $.ajaxSetup({
		// 			  headers: {
		// 				  'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
		// 			  }
		// 			});
		// 			jQuery.ajax({
		// 			  url: "{{ url('/checkTime') }}",
		// 			  method: 'post',
		// 			  data: {
		// 				 start_time: jQuery('#from-time').val(),
		// 				 end_time: jQuery('#end-time').val(),
		// 				 event_date: jQuery('#event-date').val()
		// 			  },
		// 			  success: function(result){
		// 				  if(!result.success){
		// 					  jQuery(".messageError").show();
		// 					  jQuery(".messageError").html("موعد الحجز غير متاح");
		// 				  } else {
		// 					  jQuery(".messageError").hide();
		// 				  }
		// 			}});
		// 		}

        //     });
			
			
      </script>
	  
	  
	  <script>
		function checkTime() {
				
				var fromDate = jQuery('#from-time').val();
			   var toDate = jQuery('#end-time').val();
			   
			   if(fromDate != '' && toDate != '') {
				   $.ajaxSetup({
					  headers: {
						  'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
					  }
					});
					jQuery.ajax({
					  url: "{{ url('/checkTime') }}",
					  method: 'post',
					  data: {
						 start_time: jQuery('#from-time').val(),
						 end_time: jQuery('#end-time').val(),
						 event_date: jQuery('#event-date').val()
					  },
					  success: function(result){
						  if(!result.success){
							  jQuery(".messageError").show();
							  jQuery(".messageError").html("موعد الحجز غير متاح");
						  } else {
							  jQuery(".messageError").hide();
						  }
					}});
				}
			}
	  </script>
	
	<script>
         jQuery(document).ready(function(){
            jQuery('#from-time').on("input",":text",function(){
			   
			   var fromDate = jQuery('#from-time').val();
			   var toDate = jQuery('#end-time').val();
			   
			   if(fromDate != '' && toDate != '') {
				   $.ajaxSetup({
					  headers: {
						  'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
					  }
					});
					jQuery.ajax({
					  url: "{{ url('/checkTime') }}",
					  method: 'post',
					  data: {
						 start_time: jQuery('#from-time').val(),
						 end_time: jQuery('#end-time').val(),
						 event_date: jQuery('#event-date').val()
					  },
					  success: function(result){
						  if(!result.success){
							  jQuery(".messageError").show();
							  jQuery(".messageError").html("موعد الحجز غير متاح");
						  } else {
							  jQuery(".messageError").hide();
						  }
					}});
				}
               });
            });
      </script>
	  
	  <script>
         jQuery(document).ready(function(){
            jQuery('#end-time').on("input",function(){
			   
			   var fromDate = jQuery('#from-time').val();
			   var toDate = jQuery('#end-time').val();
			   
			   if(fromDate != '' && toDate != '') {
				   $.ajaxSetup({
					  headers: {
						  'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
					  }
					});
					jQuery.ajax({
					  url: "{{ url('/checkTime') }}",
					  method: 'post',
					  data: {
						 start_time: jQuery('#from-time').val(),
						 end_time: jQuery('#end-time').val(),
						 event_date: jQuery('#event-date').val()
					  },
					  success: function(result){
						  if(!result.success){
							  jQuery(".messageError").show();
							  jQuery(".messageError").html("موعد الحجز غير متاح");
						  } else {
							  jQuery(".messageError").hide();
						  }
					}});
				}
               });
            });
      </script>

<script src="{{ URL::asset('js/manager/moment.js') }}"></script>
	 
<script src="{{ URL::asset('js/manager/combodate.js') }}"></script>
	 
	 <script>
$(function(){
    $('#from-time').combodate({
        firstItem: 'name', //show 'hour' and 'minute' string at first item of dropdown
        minuteStep: 1
    });

 $('#end-time').combodate({
        firstItem: 'name', //show 'hour' and 'minute' string at first item of dropdown
        minuteStep: 1
    });	

    /* Temporarily Solution to remove the minutes option  */
    
    $("#from-time").parent().find("select.minute option:first").remove();
    $("#end-time").parent().find("select.minute option:first").remove();

});
</script>

@stop