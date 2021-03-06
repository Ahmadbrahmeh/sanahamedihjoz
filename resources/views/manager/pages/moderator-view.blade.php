@extends('layouts.manager')

@section('pageTitle', 'معلومات المشرف')

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('css/manager/moderator.css') }}">
@stop

@section('content')
    <h1 class="h3 mb-4 text-gray-800 main-title">معلومات المشرف</h1>
    <div class="row">
        <div class="col-lg-9 main-panel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">البيانات الخاصة بالمشرف</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table_direction">
                            <tbody>
                                <tr>
                                    <td>اسم المشرف</td>
                                    <td>{{ $moderator->name }}</td>
                                </tr>
                                <tr>
                                    <td>العنوان</td>
                                    <td>{{ $moderator->address }}</td>
                                </tr>
                                <tr>
                                    <td>البريد الالكتروني</td>
                                    <td>{{ $moderator->email }}</td>
                                </tr>
                                <tr>
                                    <td>حالة الحساب</td>
                                    <td>{{ $moderator->active }}</td>
                                </tr>
                                <tr>
                                    <td>رقم الهوية</td>
                                    <td>{{ $moderator->certifcate }}</td>
                                </tr>
                                <tr>
                                    <td>عنوان الفيسبوك</td>
                                    <td><a href="{{ $moderator->fb_link }}" target="_blank">{{ $moderator->fb_link }}</a></td>
                                </tr>
                                <tr>
                                    <td>رقم الهاتف الاول</td>
                                    <td>{{ $moderator->phone1 }}</td>
                                </tr>
                                <tr>
                                    <td>رقم الهاتف الثاني</td>
                                    <td>{{ $moderator->phone2 }}</td>
                                </tr>
                                <tr>
                                    <td>رقم الهاتف الثالث</td>
                                    <td>{{ $moderator->phone3 }}</td>
                                </tr>
                                <tr>
                                    <td>كلمة السر المؤقتة</td>
                                    <td>{{ $moderator->temp_password }}</td>
                                </tr>
                                <tr>
                                    <td>تاريخ الإنشاء</td>
                                    <td>{{ $moderator->created_at }}</td>
                                </tr>
                                <tr>
                                    <td>تاريخ التعديل</td>
                                    <td>{{ $moderator->updated_at }}</td>
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
                                                    <a href="{{ route('moderator-download', $attachment->id) }}"><span>{{$attachment->name}}</span></a>
                                                </li>
                                            </ul>
                                        </li>
                                    @else
                                    <li>
                                        <ul class="image-attachment">
                                            <li>
                                                <a href="{{ route('moderator-download', $attachment->id) }}">
                                                    <img class="icons" src="{{ URL::asset('images/'.$icons[$attachment->type]) }}"/> 
                                                </a>
                                            </li>
                                            <li>
                                                <span><a href="{{ route('moderator-download', $attachment->id) }}">{{$attachment->name}}</a></span>    
                                            </li>
                                        </ul>
                                    </li>
                                    @endif
                                    @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>       
                        <div class="row">
                            <div class="col-md-12">
                                <a href="/manager/moderator/lookup"><button type="button" class="btn btn-secondary btn-back">الرجوع الى الخلف</button></a>
                            </div>
                        </div>                                       
                    </div>    
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{ URL::asset('js/manager/moderator.js') }}"></script> 
@stop