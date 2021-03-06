@extends('layouts.manager')

@section('pageTitle', 'تأجيل الحجز')

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('css/manager/reservation.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('css/time-duration.css') }}" >
@stop

@section('content')

<h1 class="h3 mb-4 text-gray-800 main-title">تأجيل الحجز</h1>
<div class="row">
    <div class="col-lg-12 main-panel">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">تعديل معلومات الحجز</h6>
            </div>
            <div class="card-body">
                <div class="col-md-5">
                    <form action="{{ route('reservation-update-date', $reservation_id) }}"  class="form-admin" id="event-form" method="post">
                        @method('put')
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="event_date" class="lbl"> تاريخ الحجز </label>
                                    <input id="event-date" type="date" value="{{ $event->reservation_date }}"  name="event_date" class="form-control" value="">
                                </div>
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-12">
                                <label for="resrvation-time" class="lbl"> وقت الحجز </label>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group right">
                                    <label for="start-time" class="lbl"> من </label>
                                    <input type="time" value="{{ $event->from_time }}" id="start-time"  name="start_time" data-validation='required' step="1800" class="form-control col-md-12">
                                </div>
                                <div class="form-group right">
                                    <label for="end-time" class="lbl"> الى </label>
                                    <input  type="time" value="{{ $event->to_time }}" id="end-time" name="end_time" data-validation='required' step="1800" class="form-control col-md-12">
                                </div>
                                <div class="form-group">
                                    <label for="preparation-time" class="lbl">وقت التحضير بين المناسبات</label>
                                    <input type="text" id="preparation" data-name-minutes="preparation_minutes" data-name-hours="preparation_hours" data-value-hours="{{ $organization->prepare_duration_hours }}" data-value-minutes="{{ $organization->prepare_duration_minutes }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success btn-block" id="save">حفظ</button>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('reservation-edit', $reservation_id) }}" type="button" class="btn btn-secondary btn-block" onclick="history.back()">الغاء</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>     
</div>
@stop

@section('scripts')
    <script src="{{ URL::asset('js/manager/reservation-info.js') }}"></script>
    <script src="{{ URL::asset('js/time-duration.js') }}"></script>
    <script>
        validateForm("event-form");
        $("#preparation").durationPicker();

    @if (session('success') == 'true')
        SuccessAlert("{{ session('message') }}");
        scrollPageTop();
    @elseif(session('success') == 'false')
        ErrorAlert("{{ session('message') }}");
        scrollPageTop();
    @endif
</script>
@stop