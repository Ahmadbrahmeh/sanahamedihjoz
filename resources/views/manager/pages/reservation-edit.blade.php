@extends('layouts.manager')

@section('pageTitle', 'تعديل الحجز')

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('css/manager/reservation.css') }}" />

@stop

@section('content')

<h1 class="h3 mb-4 text-gray-800 main-title">تعديل الحجز</h1>
<div class="row">
    <div class="col-lg-12 main-panel">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">تعديل معلومات الحجز</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            <div>
                                <span class="label-field">اسم الزبون :  </span><span id="customer-name">{{ $reservation->customer_name }}</span>
                            </div>
                            <div>
                                <span class="label-field">عنوان المناسبة :  </span><span id="reservation-title">{{ $reservation->title }}</span>
                            </div>
                            <div>
                                <span class="label-field">رقم الحجز: </span><span id="reservation-code">{{ $reservation->code }}</span>
                            </div>
                        </div>
                    </div>                
                </div>
                <div class="form-group">
                    <a href="{{ route('reservation-update', $reservation->id) }}" type="button" class="btn btn-primary" id="btn-add-hall" >تعديل معلومات الحجز</a>
                    <a href="{{ route('reservation-update-date', $reservation->id) }}" class="btn btn-info {{ $reservation->disable_delay ? 'disabled' : ''}}" id="btn-add-hall" >تحديد وقت التأجيل</a>
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