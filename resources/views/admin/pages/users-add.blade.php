@extends('layouts.admin')

@if ($action == "add")
    @section('pageTitle', 'اضافة مستخدمين')
@elseif ($action == "edit")
    @section('pageTitle', 'تعديل معلومات المستخدمين')
@endif

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('css/admin/users.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/wickedpicker.css') }}">
@stop

@section('content')
    <span id="address-list-url" hidden>/admin/address</span>
    <span id="max-attachments" hidden>{{ $maxAttachments }}</span>
    <span id="attachments-count" hidden>{{ $attachments_count }}</span>
    <h3 class="mt-4 main-title">المستخدمين</h3>

    <ol class="breadcrumb col-md-12 mb-4">
        <li class="breadcrumb-item"><a href="#"> الرئيسية </a></li>
        <li class="breadcrumb-item"><a href="/admin/users/add"> المستخدمين </a></li>
        <li class="breadcrumb-item active">{{ $action == "add"? "اضافة مستخدمين": " تعديل معلومات المستخدم " }} </li>
   </ol>    
    <div class="card mb-4">
        <div class="card-header main-panel">{{ $action == "add"? "اضافة مستخدمين": "تعديل معلومات المستخدم" }}</div>
            <div class="card-body">
                <form action="{{ $action == 'add' ? route('user-add') : route('user-edit', $user->id) }}" class="form-admin" id="add-user" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    @if ($action == "edit")
                        @method('put')
                    @endif
                    <div class="row col-md-9 table_direction main-form">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('fname') ? ' has-error' : '' }}">
                                        <label for="fname" class="lbl">الاسم الاول </label>
                                        <input id="fname" type="text" name="fname" value="{{ $user->fname ?? Request::old('fname') }}" class="form-control {{ $errors->has('fname') ? ' is-invalid error' : '' }}" data-validation="required">
                                        @if ($errors->has('fname'))
                                            <span class="help-block form-error">{{ $errors->first('fname') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('lname') ? ' has-error' : '' }}">
                                        <label for="lname" class="lbl">الاسم الأخير </label>
                                        <input id="lname" type="text" name="lname" value="{{ $user->lname ?? Request::old('lname') }}" class="form-control {{ $errors->has('lname') ? ' is-invalid error' : '' }}" data-validation="required">
                                        @if ($errors->has('lname'))
                                            <span class="help-block form-error">{{ $errors->first('lname') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('organization_name') ? ' has-error' : '' }}">
                                        <label for="organization-name" class="lbl">اسم المؤسسة</label>
                                        <input id="organization-name" type="text" name="organization_name" value="{{ $user->organization_name ?? Request::old('organization_name') }}" class="form-control {{ $errors->has('organization_name') ? ' is-invalid error' : '' }}" data-validation="required">
                                        @if ($errors->has('organization_name'))
                                            <span class="help-block form-error">{{ $errors->first('organization_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email" class="lbl">البريد الالكتروني</label>
                                        <input id="email" type="text" name="email"  class="form-control {{ $errors->has('email') ? ' is-invalid error' : '' }}" value="{{ $user->email ?? Request::old('email') }}" data-validation="required email" {{  $action == 'edit' ? "disabled" : ""}}>
                                        @if ($errors->has('email'))
                                            <span class="help-block form-error">{{ $errors->first('email') }}</span>
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
                                    <div class="form-group">
                                        <label for="organization_type" class="lbl">قطاع العمل</label>
                                        <select class="form-control" id="organization_type" name="organization_type" data-validation="required" data-validation="required email" {{  $action == 'edit' ? "disabled" : ""}}> 
                                            <option value="1">صالات</option>
                                        </select>
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('certifcate') ? ' has-error' : '' }}">
                                        <label for="certifcate" class="lbl">رقم الهوية</label>
                                        <input id="certifcate" type="text" name="certifcate"  value="{{ $user->certifcate ?? Request::old('certifcate') }}"  class="form-control {{ $errors->has('certifcate') ? ' is-invalid error' : '' }}">
                                        @if ($errors->has('certifcate'))
                                            <span class="help-block form-error">{{ $errors->first('certifcate') }}</span>
                                        @endif
                                    </div>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group {{ $errors->has('fb_link') ? ' has-error' : '' }}">
                                        <label for="fb-link" class="lbl">عنوان الفيس بوك</label>
                                        <input id="fb-link" type="text" name="fb_link" value="{{ $user->fb_link ?? Request::old('fb_link') }}" class="form-control {{ $errors->has('fb_link') ? ' is-invalid error' : '' }}" data-validation="url" data-validation-optional="true">
                                        @if ($errors->has('fb_link'))
                                            <span class="help-block form-error">{{ $errors->first('certifcate') }}</span>
                                        @endif
                                    </div>                  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('phone1') ? ' has-error' : '' }}">
                                        <label for="phone1" class="lbl">رقم الهاتف الاول</label>
                                        <input id="phone1" type="text" name="phone1" value="{{ $user->phone1 ?? Request::old('phone1') }}" class="form-control {{ $errors->has('phone1') ? ' is-invalid error' : '' }}" data-validation="required">
                                        @if ($errors->has('phone1'))
                                            <span class="help-block form-error">{{ $errors->first('phone1') }}</span>
                                        @endif
                                    </div>
                                </div>  
                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('phone2') ? ' has-error' : '' }}">
                                        <label for="phone2" class="lbl">رقم الهاتف الثاني</label>
                                        <input id="phone2" type="text" name="phone2" value="{{ $user->phone2 ?? Request::old('phone2') }}" class="form-control {{ $errors->has('phone2') ? ' is-invalid error' : '' }}">
                                        @if ($errors->has('phone2'))
                                            <span class="help-block form-error">{{ $errors->first('phone2') }}</span>
                                        @endif
                                    </div>
                                </div>  
                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('phone3') ? ' has-error' : '' }}">
                                        <label for="phone3" class="lbl">رقم الهاتف الثالث</label>
                                        <input id="phone3" type="text" name="phone3" value="{{ $user->phone3 ?? Request::old('phone3') }}" class="form-control {{ $errors->has('phone3') ? ' is-invalid error' : '' }}">
                                        @if ($errors->has('phone3'))
                                            <span class="help-block form-error">{{ $errors->first('phone3') }}</span>
                                        @endif
                                    </div>
                                </div>    
                            </div>
                            @if ($action == "add")
                            <div class="row"> 
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="temp_password" class="lbl">كلمة مرور مؤقتة</label>
                                        <input id="temp_password" type="text" name="temp_password" class="form-control" data-validation="required length" data-validation-length="min8">
                                    </div>
                                </div>    
                            </div> 
                            @endif                                             
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('from_time') ? ' has-error' : '' }}">
                                        <label for="from_time" class="lbl">من</label>                     
                                        <input type="text" name="from_time" id="from_time"  class="timepicker form-control time {{ $errors->has('from_time') ? ' is-invalid error' : '' }}"/>
                                        @if ($errors->has('from_time'))
                                            <span class="help-block form-error">{{ $errors->first('from_time') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('to_time') ? ' has-error' : '' }}">
                                        <label for="to_time" class="lbl">الى </label>                     
                                        <input type="text" name="to_time" id="to_time" class="timepicker form-control time {{ $errors->has('to_time') ? ' is-invalid error' : '' }}"/>                                        
                                        @if ($errors->has('to_time'))
                                            <span class="help-block form-error">{{ $errors->first('to_time') }}</span>
                                        @endif
                                    </div>
                                </div>            
                            </div> 
                            <div class="row">
                                <div class="col-md-12 col-xs-6"><br>
                                    <div class="form-group">
                                        <label for="form_name" class="lbl">ايام العمل الرسمية</label>                     
                                    </div>
                                    <div class="form-group" >
                                        <ul id="days-on-off">
                                            <li>
                                                <input type="checkbox" name="saturday" id="saturday" {{ $action =='edit' ? $working_days['saturday'] : ""}}>
                                                <label for="saturday">السبت</label>
                                            </li>                                            
                                            <li>
                                                <input type="checkbox" name="sunday" id="sunday" {{ $action =='edit' ? $working_days['sunday'] : ""}}>
                                                <label for="sunday">الأحد</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="monday" id="monday" {{ $action =='edit' ? $working_days['monday'] : ""}}>
                                                <label for="monday">الأثنين</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="tuesday" id="tuesday" {{ $action =='edit' ? $working_days['tuesday'] : ""}}>
                                                <label for="tuesday">الثلاثاء</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="wednesday" id="wednesday" {{ $action =='edit' ? $working_days['wednesday'] : ""}}>
                                                <label for="wednesday">الأربعاء</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="thursday" id="thursday" {{ $action =='edit' ? $working_days['thursday'] : ""}}>
                                                <label for="thursday">الخميس</label>                        
                                            </li>
                                            <li>
                                                <input type="checkbox" name="friday" id="friday" {{ $action =='edit' ? $working_days['friday'] : ""}}>
                                                <label for="friday">الجمعة</label>
                                            </li>
                                        </ul>
                                    </div>                    
                                </div>
                            </div>  
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="form_name" class="lbl">المرفقات</label>                     
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
                                                        <a href="{{ route('user-download', $attachment->id) }}"><span>{{$attachment->name}}</span></a>
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
                    </div>
                </form>                                   
            </div>
        </div>      
@stop

@section('scripts')
<script src="{{ URL::asset('js/wickedpicker.js') }}"></script>
<script src="{{ URL::asset('js/admin/users.js') }}"></script> 
<script src="{{ URL::asset('js/address.js') }}"></script>
<script src="{{ URL::asset('js/fileValidator.js') }}"></script>
<script>
    validateForm("add-user");

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
<script>
/* timepicker */

$('#from_time').wickedpicker( {
    now: "{{ Request::old('from_time') ??  $user->from_time ?? '14:00'}}",
    title:"من الساعة",
    timeSeparator: ":",
    twentyFour: true
});

$('#to_time').wickedpicker( {
    now: "{{ Request::old('to_time') ??  $user->to_time ?? '23:00'}}",
    title:"الى الساعة",
    timeSeparator: ":",
    twentyFour: true
}); 


</script>

@stop