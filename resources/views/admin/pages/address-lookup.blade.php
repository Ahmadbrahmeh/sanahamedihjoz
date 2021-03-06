@extends('layouts.admin')

@section('pageTitle', 'العناوين الجغرافية')

@section('styles')
@stop

@section('content')
    @include('admin.modals.delete-modal', ['url' => route('address-delete')])
    <span id="datatables-url" hidden>{{ route('address-datatable') }}</span>
    <span id="address-add-url" hidden>{{ route('address-add', ['code' => $code]) }}</span>
    <span id="address-code" hidden>{{ $code }}</span>
    <h3 class="mt-4 main-title">ادارة العناوين الجغرافية</h3>
    <ol class="breadcrumb col-md-12 mb-4">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"> الرئيسية </a></li>
        <li class="breadcrumb-item {{ $breadcrumbs->isEmpty() ? 'active' : '' }}"><a {{ $breadcrumbs->isEmpty() ? "" : "href=/admin/address/lookup" }} > العناوين الجغرافية </a></li>
        @foreach($breadcrumbs as $breadcrumb)
        <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
            @if(!$loop->last)
            <a href="/admin/address/lookup?code={{ $breadcrumb->code }}"> {{ $breadcrumb->name }} </a>
            @else
            {{ $breadcrumb->name }}
            @endif
        </li>
        @endforeach
    </ol>
    <div class="card mb-4">
        <div class="card-header main-panel">عرض العناوين الجغرافية </div>
        <div class="row">
            <div class="col-md-12 add-item-group">
                <div class="form-group">
                    <button class="btn btn-success right" onclick="navigateTo($('#address-add-url').text())">
                        <span class="fa fa-plus"></span>&nbsp;اضافة {{ $title }}
                    </button>
                </div>
            </div>                
        </div>
        <div class="card-body">
        <div class="table-responsive">
            <table id="dataTables_address" class="table table-bordered table_direction">
                <thead>
                    <tr>
                    <th class="no-sort">#</th>
                    <th>اسم ال{{ $title }}</th>
                    <th>كود ال{{ $title }} </th>                          
                    <th>الاجراءات</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>            
        </div>
    </div>    
@stop

@section('scripts')
<script src="{{ URL::asset('js/admin/address.js') }}"></script>
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