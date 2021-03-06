@extends('layouts.manager')

@section('pageTitle', 'عرض الحجوزات')


@section('content')
@include('manager.modals.cancel-reservation-modal', ['url' => route("reservation-cancel")])
<span id="datatables-url" hidden>{{ route('reservation-datatable') }}</span>
<h1 class="h3 mb-4 text-gray-800 main-title">الحجوزات</h1>
    <div class="row">
        <div class="col-lg-12 main-panel ">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">عرض الحجوزات</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTables_reservation" class="table table-bordered table_direction">
                            <thead> 
                                <tr>
                                    <th class="no-sort">#</th>
                                    <th>كود الحجز</th>
                                    <th>اسم الزبون</th>
                                    <th>عنوان المناسبة</th>
                                    <th>حالة الحجز</th>
                                    <th>رقم الهاتف</th>
                                    <th>حجز بواسطة</th>                              
                                    <th>تاريخ الإنشاء</th>	
                                    <th>تاريخ التعديل</th>                                 
                                    <th>اجراءات</th>                                 
                                </tr>
                            </thead>
                            <tbody> 
 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
<script src="{{ URL::asset('js/manager/reservation.js') }}"></script>
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