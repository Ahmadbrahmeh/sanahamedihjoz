@extends('layouts.manager')

@section('pageTitle', 'معلومات الحجز')

@section('styles')
@stop

@section('content')
@include('manager.modals.cancel-reservation-modal', ['url' => route("reservation-cancel")])
@include('manager.modals.delay-reservation-modal', ['url' => route("reservation-delay")])

    <h1 class="h3 mb-4 text-gray-800 main-title">معلومات الحجز</h1>
    <div class="row">
        <div class="col-lg-9 main-panel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">البيانات الخاصة بالحجز </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table_direction">
                            <tbody>
                                <tr>
                                    <td>المناسبة</td>
                                    <td>{{ $reservation->title }}</td>
                                </tr>
                                <tr>
                                    <td>اسم الحاجز</td>
                                    <td>{{ $reservation->customer_name }}</td>
                                </tr>
                                <tr>
                                    <td>كود الحجز</td>
                                    <td>{{ $reservation->code }}</td>
                                </tr>
                                <tr>
                                    <td>تكلفة الحجز</td>
                                    <td>{{ $reservation->total_cost }}</td>
                                </tr>
                                <tr>
                                    <td>المبلغ المدفوع</td>
                                    <td>{{ $reservation->deposit_amount }}</td>
                                </tr>
                                <tr>
                                    <td>المبلغ المتبقي</td>
                                    <td>{{ $reservation->remaining_amount }}</td>
                                </tr>
                                <tr>
                                    <td>الخدمات</td>
                                    <td>{{ $reservation->services }}</td>
                                </tr>
                                <tr>
                                    <td>حالة الحجز</td>
                                    <td>{{ $reservation->status }}</td>
                                </tr>
                                <tr>
                                    <td>رقم الهاتف الاول</td>
                                    <td>{{ $reservation->customer()->phone1 }}</td>
                                </tr>
                                <tr>
                                    <td>رقم الهاتف الثاني</td>
                                    <td>{{ $reservation->customer()->phone2 }}</td>
                                </tr>
                                <tr>
                                    <td>رقم الهاتف الثالث</td>
                                    <td>{{ $reservation->customer()->phone3 }}</td>
                                </tr>
                                <tr>
                                    <td>العنوان</td>
                                    <td>{{ $reservation->customer_address }}</td>
                                </tr>
                                <tr>
                                    <td>رقم الهوية</td>
                                    <td>{{ $reservation->customer()->certifcate }}</td>
                                </tr>
                                <tr>
                                    <td>تم الحجز بواسطة</td>
                                    <td>{{ $reservation->creator()->name }}</td>
                                </tr>
                                <tr>
                                    <td>تاريخ الإنشاء</td>
                                    <td>{{  $reservation->created_at }}</td>
                                </tr>
                                <tr>
                                    <td>تاريخ التعديل</td>
                                    <td>{{  $reservation->updated_at }}</td>
                                </tr>
                            </tbody>
                        </table>                                      
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 main-panel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">المناسبات</h6>
                </div>
                <div class="card-body">
                    <table id="table_payments" class="table table-bordered table_direction">
                        <thead>
                            <tr>
                                <th class="no-sort">#</th>
                                <th>القاعة</th>
                                <th>تاريخ الحجز</th>
                                <th>من</th>
                                <th>الى</th>
                            </tr>
                        </thead>
                        <tbody> 
                        @foreach( $reservation->events as $event)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $event->hall()->name }}</td>
                                <td>{{ $event->reservation_date }}</td>
                                <td>{{ $event->from_time }}</td>
                                <td>{{ $event->to_time }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
        <div class="col-lg-9 main-panel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">الاجراءات</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @if(auth()->user()->manager()->type == 1)              
                        <button type="button" class="btn btn-danger" data-id="{{ $reservation->id }}" {{ $reservation->disable_cancel ? 'disabled' : ''}} data-id="{{ $reservation->id }}"  data-toggle="modal" data-target="#cancelReservationModal">الغاء الحجز</button>
                        <button type="button" class="btn btn-info" data-id="{{ $reservation->id }}"  data-toggle="modal"  data-target="#delayReservationModal" {{ ($reservation->disable_cancel || $reservation->disable_delay) ? 'disabled' : ''}} >تأجيل الحجز</button>
                        @endif
                       <!-- <a href="{{ route('payment-add', $reservation->id) }}" class="btn btn-primary  {{ $reservation->disable_cancel ? 'disabled' : ''}}">دفع المستحقات</a> -->
					    <a href="{{ route('receipt-add') }}" class="btn btn-primary  {{ $reservation->disable_cancel ? 'disabled' : ''}}">دفع المستحقات</a>
                        <a href="{{ route('reservation-update', $reservation->id) }}" class="btn btn-success  {{ $reservation->disable_cancel ? 'disabled' : ''}}">تعديل الحجز</a>
                    </div>
                </div>
            </div>
        </div>        
        <div class="col-lg-9 main-panel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">الفواتير</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">              
                        <button type="button" class="btn btn-primary btn-icon-split" id="reservation-eventlist-btn" data-url="{{ route('reservation-eventlist-sheet', $reservation->code) }}">
                            <span class="icon text-white-50">
                                <i class="fas fa-clipboard	"></i>
                            </span>
                            &nbsp; جدول المناسبات &nbsp;&nbsp;
                        </button>
                        <button type="button" class="btn btn-primary btn-icon-split" id="reservation-invoice-btn" data-url="{{ route('reservation-invoice', $reservation->code) }}">
                            <span class="icon text-white-50">
                                <i class="fas fa-clipboard	"></i>
                            </span>
                            &nbsp;فاتورة الحجز &nbsp;&nbsp;
                        </button>
                        <a class="btn btn-primary btn-icon-split" href="{{ route('report-account-lookup', ['customer_id' =>  $reservation->customer()->id]) }}" >
                            <span class="icon text-white-50">
                                <i class="fas fa-clipboard	"></i>
                            </span>
                            &nbsp;فاتورة كشف الحساب&nbsp;&nbsp;
                        </a>
                    </div>
                </div>
            </div>
        </div>        
        <div class="col-lg-9 main-panel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">الدفعات من الزبون</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatables_payments" class="table table-bordered table_direction">
                            <thead>
                                <tr>
                                    <th class="no-sort">#</th>
                                    <th>رقم الفاتورة</th>
                                    <th>مجموع المقبوضات نقدا</th>
                                    <th>مجموع المقبوضات بالشيكات</th>
                                    <th>المجموع</th>
                                    <th>العملة</th>
                                    <th>الاجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach( $reservation->payments() as $payment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $payment->invoice_number }}</td>
                                    <td>{{ $payment->total_cash }}</td>
                                    <td>{{ $payment->total_cheque }}</td>
                                    <td>{{ $payment->total }}</td>
                                    <td>{{ $reservation->currency()->name }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-circle btn-receipt" data-url="{{ route('payment-receipt' , [$reservation->code,  $payment->id]) }}">
                                            <span class="fas fa-clipboard"  data-url="{{ route('payment-receipt' , [$reservation->code,  $payment->id]) }}"></span>
                                        </button>
                                        <!-- <a href="#" class="btn btn-info btn-circle">
                                            <span class="fas fa-edit"></span>
                                        </a> -->
                                        </a>    
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>        
        <div class="col-lg-9 main-panel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">جدول الملاحظات</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">              
                        <table class="table table-striped table_direction">
                            <tr>
                                <th>#</th>
                                <th>الملاحظة</th>
                            </tr>
                            @foreach( $reservation->notes()->where("mark_for_delete", false) as $note)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $note->value }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>        
        <div class="col-lg-9 main-panel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">جدول الالتزامات والشروط</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">              
                        <table class="table table-striped table_direction">
                            <tr>
                                <th>#</th>
                                <th>الشرط</th>
                            </tr>
                            @foreach( $reservation->terms()->where("mark_for_delete", false) as $term)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $term->value }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>        
    </div>
@stop

@section('scripts')
    <script src="{{ URL::asset('js/manager/reservation-info.js') }}"></script>
    <script>
    @if (session('success') == 'true')
        SuccessAlert("{{ session('message') }}");
        scrollPageTop();
    @elseif(session('success') == 'false')
        ErrorAlert("{{ session('message') }}");
        scrollPageTop();
    @endif
</script>
@stop