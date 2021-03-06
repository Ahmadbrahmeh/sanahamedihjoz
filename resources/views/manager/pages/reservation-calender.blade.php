@extends('layouts.manager')

@section('pageTitle', 'التقويم')

@section('styles')

<link rel="stylesheet" href="{{ URL::asset('css/manager/calendar.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('css/manager/reservation.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('js/manager/packages/core/main.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('js/manager/packages/daygrid/main.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('js/manager/packages/timegrid/main.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('js/manager/packages/list/main.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('js/manager/packages/bootstrap/main.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('js/manager/packages/daygrid/main.css') }}" />
@stop

@section('content')

<style type="text/css">
    .fc-toolbar.fc-header-toolbar {
        padding-top: 0px !important;
    }   

    .card-header {
        padding: 5px !important;
    } 

    .card-body {
        padding: 5px !important;
    }

    .topbar {
        height: 40px !important;
    }

    nav.navbar.navbar-expand.navbar-light.bg-white.topbar.mb-4.static-top.shadow {
        margin-bottom: 5px !important;
    }

    .fc-toolbar.fc-header-toolbar{
        margin-bottom: 5px !important;
    }

    .fc-row.fc-week.table-bordered{
        min-height: 90px !important;
    }

    .fc-toolbar.fc-header-toolbar.row {
        padding: 0px !important;
        margin-left: 0px !important;
        margin-right: 0px !important;
    }

    .header-toolbar-section1 {
        text-align: left;
    }

    .header-toolbar-section2 {
        text-align: center;
    }

    .header-toolbar-section3 {
        text-align: right;
    }

    @media only screen and (max-width: 575px) {
        .header-toolbar-section1 {
            text-align: center;
        }

        .header-toolbar-section2 {
            text-align: center;
        }

        .header-toolbar-section3 {
            text-align: center;
        }
    }


a.btn.btn-primary.btnTab {
    background-color: #6699ff;
    border: #6699ff;
}

.fc-head tr .fc-head-container .fc-row.table-bordered .table-bordered thead tr th {
    border: 1px solid #fff !important;
}

.fc-head tr .fc-head-container .fc-row.table-bordered .table-bordered thead > tr:nth-of-type(1) {
    background: #006699;
    color: white;
    line-height: 20px;
}

.table-bordered td, .table-bordered th{
    border: 1px solid #006699;
}

.fc-scroller.fc-day-grid-container {
    height: auto !important;
}

a.fc-day-number{
    color: #333 !important;
}

.fc-nonbusiness {
    background: #999 !important;
}

.fc-rtl .fc-dayGrid-view .fc-day-top .fc-day-number {
    float: right !important;
}

.fc-content {
    text-align: right !important;
}
#active-border {
    border-style:solid;
    border-width:3px;
    border-color:navy;
    padding: .375rem .75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: .35rem;
}
</style>

    @if($halls->count() > 0) 
        @include('manager.modals.reservation-modal', ['hall' => $organization->hall->id])
    @endif
    <input type="hidden" id="work-days" value="{{$organization->work_days }}" />
    <input type="hidden" id="off-days" value="{{ $organization->off_days }}" />
    <input type="hidden" id="start-work" value="{{ $organization->from_time }}" />
    <input type="hidden" id="end-work" value="{{ $organization->to_time }}" />
   <!-- <h1 class="h3 mb-4 text-gray-800 main-title"> الحجوزات </h1> -->
    <div class="row">
        <div class="col-lg-12 main-panel">
            <div class="card shadow mb-4">
                <div class="row" style="direction: rtl;margin: 0px !important;">
                    <div class="col-12 col-xs-4 col-sm-4 col-md-4 col-lg-4 card-header">
                        <h6 class="m-0 font-weight-bold text-primary" style="line-height: 25px;">الحجوزات (التقويم)</h6>
                    </div>
                    <div class="col-12 col-xs-8 col-sm-8 col-md-8 col-lg-8 card-header">
                        <!--<label for="hall" class="lbl-currency col-md-12">القاعة</label> -->

                            
                    <div id="myDIV">
                        @foreach($halls as $hall)
                        <a href="{{ route('reservation-calender', ['hall' => $hall->id]) }}" class="btn btn-primary btnTab {{ ($organization->hall->id == $hall->id) ? 'active' : '' }}" id="{{ ($organization->hall->id == $hall->id) ? 'active-border' : '' }}">
                            {{ $hall->name }}
                        </a>
                        @endforeach
                    </div>

                        <!-- <select class="form-control" onchange="location = this.value;" id="hall" name="hall"> 
                            @foreach($halls as $hall)
                            <option value="{{ route('reservation-calender', ['hall' => $hall->id]) }}" {{ ($organization->hall->id == $hall->id) ? "selected" : "" }} >{{ $hall->name }}</option>
                            @endforeach
                        </select> -->
                    </div>
                </div>
                <div class="card-body">
                    
                    <div id='calendar-container'>
                        <div id='calendar'>

                        </div>   
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script> 
        var jsonEvents = JSON.parse(@json($events));
    </script>
    <script src="{{ URL::asset('js/sweetalert2.js') }}"></script>
    <script src="{{ URL::asset('js/sweetalert.min.js') }}"></script>
    <script src='https://unpkg.com/@fullcalendar/core@4.4.2/locales-all.js'></script>
    <script src="{{ URL::asset('js/manager/packages/core/main.js') }}"></script>
    <script src="{{ URL::asset('js/manager/packages/bootstrap/main.js') }}"></script>
    <script src="{{ URL::asset('js/manager/packages/interaction/main.js') }}"></script>
    <script src="{{ URL::asset('js/manager/packages/daygrid/main.js') }}"></script>
    <script src="{{ URL::asset('js/manager/packages/timegrid/main.js') }}"></script>
    <script src="{{ URL::asset('js/manager/packages/list/main.js') }}"></script>
    <script src="{{ URL::asset('js/manager/calendar.js') }}"></script>

@stop