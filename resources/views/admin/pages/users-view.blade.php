@extends('layouts.admin')

@section('pageTitle', 'عرض معلومات المستخدم')

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('css/admin/users.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/wickedpicker.css') }}">

@stop

@section('content')
    <span id="max-attachments" hidden>7</span>
    <h3 class="mt-4 main-title">المستخدمين</h3>
    <ol class="breadcrumb col-md-12 mb-4">
        <li class="breadcrumb-item"><a href="#"> الرئيسية </a></li>
        <li class="breadcrumb-item"><a href="/admin/users/lookup"> المستخدمين </a></li>
        <li class="breadcrumb-item active"> {{ $user->name }} </li>
   </ol>    
    <div class="card mb-4">
        <div class="card-header main-panel">معلومات المستخدم</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table_direction">
                    <tbody>
                    <tr>
                            <td>اسم المشرف</td>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td>اسم المؤسسة</td>
                            <td>{{ $user->organization_name }}</td>
                        </tr>
                        <tr>
                            <td>ايام الدوام</td>
                            <td>{{ $user->organization_working_days }}</td>
                        </tr>
                        <tr>
                            <td>اوقات الدوام</td>
                            <td> 
                            من الساعة  
                            @if( strpos($user->from_time, 'pm'))
                                <b>{{str_replace('pm', ' مساءً', $user->from_time)}}</b>
                            @else
                                <b>{{str_replace('pm', ' صباحاً', $user->from_time)}}</b>
                            @endif
                             حتى الساعة 
                         
                             @if( strpos($user->to_time, 'pm'))
                                <b>{{str_replace('pm', ' مساءً', $user->to_time)}}</b>
                            @else
                                <b>{{str_replace('pm', ' صباحاً', $user->to_time)}}</b>
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <td>العنوان</td>
                            <td>{{ $user->address }}</td>
                        </tr>
                        <tr>
                            <td>البريد الالكتروني</td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td>حالة الحساب</td>
                            <td>{{ $user->active }}</td>
                        </tr>
                        <tr>
                            <td>قطاع العمل</td>
                            <td>{{ $user->organization_type }}</td>
                        </tr>
                        <tr>
                            <td>رقم الهوية</td>
                            <td>{{ $user->certifcate }}</td>
                        </tr>
                        <tr>
                            <td>عنوان الفيسبوك</td>
                            <td><a href="{{ $user->fb_link }}" target="_blank">{{ $user->fb_link }}</a></td>
                        </tr>
                        <tr>
                            <td>رقم الهاتف الاول</td>
                            <td>{{ $user->phone1 }}</td>
                        </tr>
                        <tr>
                            <td>رقم الهاتف الثاني</td>
                            <td>{{ $user->phone2 }}</td>
                        </tr>
                        <tr>
                            <td>رقم الهاتف الثالث</td>
                            <td>{{ $user->phone3 }}</td>
                        </tr>
                        <tr>
                            <td>كلمة السر المؤقتة</td>
                            <td>{{ $user->temp_password }}</td>
                        </tr>
                        <tr>
                            <td>تاريخ الإنشاء</td>
                            <td>{{ $user->created_at->toDayDateTimeString() }}</td>
                        </tr>
                        <tr>
                            <td>تاريخ التعديل</td>
                            <td>{{ $user->updated_at->toDayDateTimeString() }}</td>
                        </tr>
                    </tbody>
                </table>                                      
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <h4>المرفقات</h4>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-12 attachments">
                            <ul class="attachments-file">
                            @foreach ($attachments as $attachment)
                            @if($attachment->type == "image")
                                <li>
                                    <ul class="image-attachment">
                                        <li>
                                            <a class="single_image" href="{{ asset('storage/'.$attachment->path) }}">
                                                <img class="image img-responsive" src="{{ asset('storage/'.$attachment->path) }}" />                                                            
                                            </a>                                                
                                        </li>
                                        <li>
                                            <a href="{{ route('user-download', $attachment->id) }}"><span>{{$attachment->name}}</span></a>
                                        </li>
                                    </ul>
                                </li>
                            @else
                            <li>
                                <ul class="image-attachment">
                                    <li>
                                        <a href="{{ route('user-download', $attachment->id) }}">
                                            <img class="icons" src="{{ URL::asset('images/'.$icons[$attachment->type]) }}"/> 
                                        </a>
                                    </li>
                                    <li>
                                        <span><a href="{{ route('user-download', $attachment->id) }}">{{$attachment->name}}</a></span>    
                                    </li>
                                </ul>
                            </li>
                            @endif
                            @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="row col-md-12">
                        <div class="col-md-12">
                            <a href="/admin/users/lookup"><button type="button" class="btn btn-secondary btn-back">الرجوع الى الخلف</button></a>
                        </div>
                    </div>                                       
            </div>
        </div>
    </div>       
@stop


@section('scripts')
    <script src="{{ URL::asset('js/admin/users.js') }}"></script>     
@stop