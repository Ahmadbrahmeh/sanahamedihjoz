@extends('layouts.manager')

@if ($action == "add")
    @section('pageTitle', 'اضافة مشرفين')
@elseif ($action == "edit")
    @section('pageTitle', 'تعديل معلومات المشرفين')
@endif

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('css/manager/moderator.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/wickedpicker.css') }}">
@stop

@section('content')
    <span id="address-list-url" hidden>/manager/address</span>
    <span id="max-attachments" hidden>{{ $maxAttachments }}</span>
    <span id="attachments-count" hidden>{{ $attachments_count }}</span>
    @if ($action == "add")
        <h1 class="h3 mb-4 text-gray-800 main-title">المشرفين</h1>
    @elseif ($action == "edit")
        <h1 class="h3 mb-4 text-gray-800 main-title">تعديل معلومات المشرفين</h1>
    @endif
    <div class="row">
        <div class="col-lg-9 main-panel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">اضافة مشرف</h6>
                </div>
                <div class="card-body">
                    <form action="{{ $action == 'add' ? route('moderator-add') : route('moderator-edit', $moderator->id) }}" class="form-admin" id="add-moderator" method="post" enctype="multipart/form-data">
                        @if ($action == "edit")
                            @method('put')
                        @endif
                        {{ csrf_field() }}
                        <div class="row col-md-12 table_direction">
                            <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('fname') ? ' has-error' : '' }}">
                                        <label for="fname" class="lbl">الاسم الاول</label>
                                        <input id="fname" type="text" name="fname" value="{{ $moderator->fname ?? Request::old('fname') }}" class="form-control {{ $errors->has('fname') ? ' is-invalid error' : '' }}" data-validation="required">
                                        @if ($errors->has('fname'))
                                            <span class="help-block form-error">{{ $errors->first('fname') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('lname') ? ' has-error' : '' }}">
                                        <label for="lname" class="lbl">الاسم الاخير</label>
                                        <input id="lname" type="text" name="lname" value="{{ $moderator->lname ?? Request::old('lname') }}" class="form-control {{ $errors->has('lname') ? ' is-invalid error' : '' }}" data-validation="required">
                                        @if ($errors->has('lname'))
                                            <span class="help-block form-error">{{ $errors->first('lname') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address" class="lbl">المدينة</label>
                                        <select class="form-control" name="address_city" id="address-city" data-validation="required">
                                            <option value="">اختر مدينة</option>
                                            @foreach($cities as $city)
                                            <option {{ $action == 'edit' ? ($address_types['city']->code == $city->code ? "selected": "") :"" }} value="{{ $city->code }}">{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group {{ isset($address_types['region']) ? '' : 'hide' }}" id="form-address-region">
                                        <label for="address" class="lbl">المنطقة</label>
                                        <select class="form-control" name="address_region" id="address-region" data-validation="required">
                                        @if($action == 'edit')
                                            <option value="">اختر منطقة</option>
                                            @foreach($regions as $region)
                                            <option {{ ( $address_types['region']->code == $region->code ? "selected": "")  }} value="{{ $region->code }}">{{ $region->name }}</option>
                                            @endforeach
                                        @endif
                                        </select>
                                    </div>
                                </div>  
                                <div class="col-md-4">
                                    <div class="form-group {{ isset($address_types['street']) ? '' : 'hide' }}" id="form-address-street">
                                        <label for="address" class="lbl">الشارع</label>
                                        <select class="form-control" name="address_street" id="address-street">
                                        @if($action == 'edit')
                                            <option value="">اختر منطقة</option>
                                            @foreach($streets as $street)
                                            <option {{ ( $address_types['street']->code == $street->code ? "selected": "")  }} value="{{ $street->code }}">{{ $street->name }}</option>
                                            @endforeach
                                        @endif
                                        </select>
                                    </div>
                                </div>        
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('certifcate') ? ' has-error' : '' }}">
                                        <label for="certifcate" class="lbl">رقم الهوية</label>
                                        <input id="certifcate" value="{{ $moderator->certifcate ?? Request::old('certifcate') }}" type="text" name="certifcate" class="form-control {{ $errors->has('certifcate') ? ' is-invalid error' : '' }}">
                                        @if ($errors->has('certifcate'))
                                            <span class="help-block form-error">{{ $errors->first('certifcate') }}</span>
                                        @endif
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email" class="lbl">البريد الالكتروني</label>
                                        <input id="email" type="text" name="email" {{  $action == 'edit' ? "disabled" : ""}} value="{{ $moderator->email ?? Request::old('email') }}" class="form-control {{ $errors->has('email') ? ' is-invalid error' : '' }}" data-validation="required email">
                                        @if ($errors->has('email'))
                                            <span class="help-block form-error">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>    
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group {{ $errors->has('fb_link') ? ' has-error' : '' }}">
                                        <label for="fb_link" class="lbl">عنوان الفيس بوك</label>
                                        <input id="fb-link" type="text" value="{{ $moderator->fb_link ?? Request::old('fb_link') }}" name="fb_link" class="form-control {{ $errors->has('fb_link') ? ' is-invalid error' : '' }}" data-validation="url" data-validation-optional="true">
                                        @if ($errors->has('fb_link'))
                                            <span class="help-block form-error">{{ $errors->first('fb_link') }}</span>
                                        @endif
                                    </div>                  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('phone1') ? ' has-error' : '' }}">
                                        <label for="phone1" class="lbl">رقم الهاتف الاول</label>
                                        <input id="phone1" type="text" name="phone1" value="{{ $moderator->phone1 ?? Request::old('phone1') }}" class="form-control {{ $errors->has('phone1') ? ' is-invalid error' : '' }}" data-validation="required">
                                        @if ($errors->has('phone1'))
                                            <span class="help-block form-error">{{ $errors->first('phone1') }}</span>
                                        @endif
                                    </div>
                                </div>  
                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('phone2') ? ' has-error' : '' }}">
                                        <label for="phone2" class="lbl">رقم الهاتف الثاني</label>
                                        <input id="phone2" type="text" name="phone2" value="{{  $moderator->phone2 ?? Request::old('phone2') }}" class="form-control {{ $errors->has('phone2') ? ' is-invalid error' : '' }}">
                                        @if ($errors->has('phone2'))
                                            <span class="help-block form-error">{{ $errors->first('phone2') }}</span>
                                        @endif
                                    </div>
                                </div>  
                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('phone3') ? ' has-error' : '' }}">
                                        <label for="phone3" class="lbl">رقم الهاتف الثالث</label>
                                        <input id="phone3" type="text" name="phone3" value="{{ $moderator->phone3 ?? Request::old('phone3') }}" class="form-control {{ $errors->has('phone3') ? ' is-invalid error' : '' }}">
                                        @if ($errors->has('phone3'))
                                            <span class="help-block form-error">{{ $errors->first('phone3') }}</span>
                                        @endif
                                    </div>
                                </div>    
                            </div>
                            @if ($action == "add")
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="temp_password" class="lbl">كلمة  سر مؤقتة</label>
                                        <input id="temp_password" type="text" name="temp_password" class="form-control"data-validation="required length" data-validation-length="min8">
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="attachments" class="lbl">المرفقات</label>                     
                                    </div>
                                </div>
                            </div>
                            @if ($action == "edit")
                            <div class="row">
                                <div class="col-md-12 attachments">
                                    <ul class="attachments-file">
                                    @foreach ($attachments as $attachment)
                                        @if($attachment->type == "image")
                                            <li>
                                                <ul class="image-attachment"> 
                                                    <li>
                                                        <button type="button" href="#" data-id="{{ $attachment->id }}" class="btn btn-danger btn-circle btn-sm attachment-delete" onclick="removeSavedField(this)">
                                                            <i class='fas fa-trash'></i>
                                                        </button>
                                                    </li>
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
                                                    <button type="button" href="#" data-id="{{ $attachment->id }}" class="btn btn-danger btn-circle btn-sm attachment-delete" onclick="removeSavedField(this)">
                                                        <i class='fas fa-trash'></i>
                                                    </button>
                                                </li>
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
                            @endif  
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group attachments-pane">
                                        <table class="attachments-table col-md-12">
                                           
                                        </table>
                                    </div>
                                    <div class="form-group add-new-attachment tab-pane">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-primary btn-icon-split" id="add-field">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-plus"></i>
                                            </span>
                                               &nbsp;اضافة مرفق جديد&nbsp;&nbsp;
                                            </button>
                                        </div>
                                    </div>
                                    <br>
                                </div>  
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-sm-3 col-xs-12 right">
                                    @if ($action == "add")
                                        <button type="submit" class="btn btn-success btn-block" id="add">اضافة</button>
                                    @elseif ($action == "edit")
                                        <button type="submit" class="btn btn-success btn-block" id="save">حفظ</button>
                                    @endif
                                </div>
                                <div class="form-group col-md-6 col-sm-3 col-xs-12 right">
                                    <button type="button" class="btn btn-secondary btn-block" onclick="history.back()">الغاء</button>
                                </div>
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
<script src="{{ URL::asset('js/wickedpicker.js') }}"></script>
<script src="{{ URL::asset('js/manager/moderator.js') }}"></script> 
<script src="{{ URL::asset('js/address.js') }}"></script>
<script src="{{ URL::asset('js/fileValidator.js') }}"></script>
<script>
    validateForm("add-moderator");

    @if (session('success') == 'true')
        SuccessAlert("{{ session('message') }}");
        scrollPageTop();
    @elseif(session('success') == 'false')
        ErrorAlert("{{ session('message') }}");
        scrollPageTop();
    @endif
    
    notifyChanges();
    function notifyChanges() {
        $(".fileInp").change(function () {
            validateFile(this);
        });
    }

    var maxSize = "5M";
    var allowedExtention = "jpg,jpeg,png,pdf,docx,doc";

    function validateFile($fileElement) {
        validateSucess= true;
        if(file = $fileElement.files[0]){
            if(!validateAllowedExtention($fileElement, allowedExtention)) {
                $fileElement.value = null;
                var textAllowedExtention = allowedExtention.split(",").join(", ");
                sweetAlert({
                    title: "فشل تحميل الملف",
                    text: "نأسف امتدادات الملفات المسموح تحميلها فقط هي<br>" + textAllowedExtention ,
                    html: true,
                    type: "error"
                });
            }
            else if(!validateFileSize($fileElement, maxSize)) {
                $fileElement.value = null;
                sweetAlert("فشل تحميل الملف", maxSize + " حجم الملف كبير جدأ الحجم المسموح به الى حد", "error");
            }
        }
    }
</script>
@stop